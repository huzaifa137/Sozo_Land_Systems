<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\BuildsSozoData;
use Tests\Support\CleansUploads;
use Tests\TestCase;

/**
 * The per-plot "Confirm" button on Pending Receipts (POST /confirm-request-permission/{id}).
 */
class PermissionConfirmationTest extends TestCase
{
    use RefreshDatabase, BuildsSozoData, CleansUploads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->snapshotUploads();
    }

    protected function tearDown(): void
    {
        $this->cleanUploads();
        parent::tearDown();
    }

    private function requestedBuyer(array $o = []): int
    {
        $id = $this->makeBuyer(array_merge(['request_permission' => 1, 'reciepts' => 'Pending', 'plot_number' => '1'], $o));
        $this->makePlot(['plot_number' => $o['plot_number'] ?? '1', 'status' => 'Partially payed', 'buyer_id' => (string) $id]);

        return $id;
    }

    private function confirm(int $id)
    {
        return $this->actingAsAdmin($this->makeAdmin())
            ->postJson("/confirm-request-permission/{$id}", ['request_permission' => 2]);
    }

    public function test_confirming_a_requested_buyer_generates_the_agreement_and_completes_the_sale(): void
    {
        $id = $this->requestedBuyer(['amount_payed' => 5000000, 'balance' => 0]);

        $this->confirm($id)->assertOk()->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Agreement generated for Jane Doe.');

        $buyer = DB::table('buyers')->find($id);
        $this->assertSame('Fully payed', $buyer->next_installment_pay);
        $this->assertSame(2, (int) $buyer->request_permission);
        $this->assertSame('-', $buyer->reciepts);
        $this->assertStringStartsWith('payment_agreement_' . $id . '_', $buyer->agreement);

        $pdf = public_path('agreements/' . $buyer->agreement);
        $this->assertFileExists($pdf);
        $this->assertStringStartsWith('%PDF', file_get_contents($pdf));

        $this->assertDatabaseHas('agreements', ['user_id' => $id, 'agreement' => $buyer->agreement]);
        $this->assertDatabaseHas('plots', ['plot_number' => '1', 'status' => 'Fully payed']);
        $this->assertDatabaseHas('reciepts', ['user_id' => (string) $id, 'reciept' => '-']);
    }

    public function test_only_the_confirmed_buyer_is_touched(): void
    {
        $a = $this->requestedBuyer(['plot_number' => '1']);
        $b = $this->requestedBuyer(['plot_number' => '2', 'firstname' => 'Other']);

        $this->confirm($a)->assertOk();

        $this->assertSame(1, (int) DB::table('buyers')->find($b)->request_permission);
        $this->assertSame('Partially payed', DB::table('plots')->where('plot_number', '2')->value('status'));
        $this->assertDatabaseCount('agreements', 1);
    }

    public function test_confirming_twice_does_not_create_a_second_agreement(): void
    {
        $id = $this->requestedBuyer();

        $this->confirm($id)->assertOk();
        $this->confirm($id)->assertStatus(422)->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Permission has already been confirmed for this buyer.');

        $this->assertDatabaseCount('agreements', 1);
        $this->assertDatabaseCount('reciepts', 1);
    }

    public function test_a_buyer_who_never_requested_permission_cannot_be_confirmed(): void
    {
        $id = $this->requestedBuyer(['request_permission' => 0]);

        $this->confirm($id)->assertStatus(422)->assertJsonPath('status', 'error');

        $this->assertSame(0, (int) DB::table('buyers')->find($id)->request_permission);
        $this->assertDatabaseCount('agreements', 0);
    }

    public function test_an_unknown_buyer_is_a_404_json_error_not_a_crash(): void
    {
        $this->confirm(99999)->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_guests_cannot_confirm(): void
    {
        $id = $this->requestedBuyer();

        $this->postJson("/confirm-request-permission/{$id}")->assertStatus(302);
        $this->assertSame(1, (int) DB::table('buyers')->find($id)->request_permission);
    }

    public function test_request_then_confirm_round_trip(): void
    {
        $id = $this->makeBuyer(['request_permission' => 0]);
        $this->makePlot(['buyer_id' => (string) $id]);
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->postJson("/update-request-permission/{$id}")->assertJson(['success' => true]);
        $this->assertSame(1, (int) DB::table('buyers')->find($id)->request_permission);

        $this->actingAsAdmin($admin)->postJson("/confirm-request-permission/{$id}")->assertJsonPath('status', 'success');
        $this->assertSame(2, (int) DB::table('buyers')->find($id)->request_permission);
    }

    public function test_the_existing_group_confirmation_still_works_unchanged(): void
    {
        $group = 'group-uuid-1';
        $a = $this->makeBuyer(['multiple_user_id' => $group, 'request_permission' => 1, 'plot_number' => '1']);
        $b = $this->makeBuyer(['multiple_user_id' => $group, 'request_permission' => 1, 'plot_number' => '2']);
        $this->makePlot(['plot_number' => '1', 'buyer_id' => (string) $a]);
        $this->makePlot(['plot_number' => '2', 'buyer_id' => (string) $b]);

        $this->actingAsAdmin($this->makeAdmin())->postJson("/confirm-group-permission/{$group}")
            ->assertOk()->assertJson(['success' => true]);

        $this->assertSame([2, 2], DB::table('buyers')->orderBy('id')->pluck('request_permission')->map(fn ($v) => (int) $v)->all());
        $this->assertDatabaseCount('agreements', 2);
    }
}
