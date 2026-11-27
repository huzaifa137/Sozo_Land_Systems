<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\BuildsSozoData;
use Tests\Support\CleansUploads;
use Tests\TestCase;

/**
 * Next-installment handling and the "never sell the same plot twice" rule.
 */
class SaleIntegrityTest extends TestCase
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

    private function sell(array $overrides = [])
    {
        return $this->actingAsAdmin($this->makeAdmin())
            ->postJson('/store-buyer-details', $this->salePayload($this->salePhotos() + $overrides));
    }

    private function nextInstallmentOf(string $plot): string
    {
        return DB::table('buyers')->where('plot_number', $plot)->value('next_installment_pay');
    }

    // ===== next_installment_pay =====

    public function test_the_installment_date_chosen_on_the_form_is_stored(): void
    {
        $this->sell(['plot_number' => '7', 'next_installment_pay' => '2027-03-15'])->assertOk();

        $this->assertSame('2027-03-15', $this->nextInstallmentOf('7'));
    }

    public function test_a_sale_paid_in_full_is_stored_as_fully_payed(): void
    {
        $this->sell(['plot_number' => '7', 'next_installment_pay' => 'Fully payed', 'balance' => 0])->assertOk();

        $this->assertSame('Fully payed', $this->nextInstallmentOf('7'));
    }

    public function test_fully_payed_is_not_trusted_when_a_balance_is_still_owing(): void
    {
        $this->sell(['plot_number' => '7', 'next_installment_pay' => 'Fully payed', 'balance' => 2500000])->assertOk();

        $stored = $this->nextInstallmentOf('7');
        $this->assertNotSame('Fully payed', $stored);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $stored);
    }

    public function test_a_missing_or_garbage_date_gets_a_future_default_never_the_old_hardcoded_date(): void
    {
        foreach (['8' => null, '9' => 'not-a-date', '10' => '15/03/2027'] as $plot => $value) {
            $this->sell(['plot_number' => (string) $plot] + ($value === null ? [] : ['next_installment_pay' => $value]))->assertOk();

            $stored = $this->nextInstallmentOf((string) $plot);
            $this->assertNotSame('2024-11-12', $stored);
            $this->assertGreaterThan(now()->toDateString(), $stored, "plot $plot");
        }
    }

    public function test_every_plot_of_a_multi_plot_sale_gets_the_chosen_date(): void
    {
        $dims = ['width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'];
        $this->sell([
            'next_installment_pay' => '2027-01-20',
            'multiple_estates' => json_encode([
                ['estate' => 'Kayabwe', 'plot_number' => '1', 'location' => 'Mukono'] + $dims,
                ['estate' => 'Kayabwe', 'plot_number' => '2', 'location' => 'Mukono'] + $dims,
            ]),
        ])->assertOk();

        $this->assertSame(['2027-01-20'], DB::table('buyers')->pluck('next_installment_pay')->unique()->values()->all());
    }

    public function test_a_sale_dated_today_shows_up_in_the_payment_reminders_it_was_missing_from(): void
    {
        $this->sell(['plot_number' => '7', 'next_installment_pay' => now()->toDateString()])->assertOk();

        $this->assertSame(1, DB::table('buyers')->whereDate('next_installment_pay', now()->toDateString())->count());
    }

    // ===== double selling =====

    public function test_the_same_plot_cannot_be_sold_twice(): void
    {
        $this->sell(['plot_number' => '7'])->assertOk();

        $this->sell(['plot_number' => '7', 'firstname' => 'Second', 'NIN' => 'CM000'])
            ->assertStatus(422)
            ->assertJson(['status' => false])
            ->assertJsonPath('message', 'Plot 7 in Kayabwe has already been sold.');

        $this->assertSame(1, DB::table('buyers')->where('plot_number', '7')->count());
        $this->assertDatabaseMissing('buyers', ['firstname' => 'Second']);
    }

    public function test_a_rejected_sale_stores_no_files(): void
    {
        $this->sell(['plot_number' => '7'])->assertOk();
        $before = count($this->filesCreatedIn(public_path('profile_pic')));

        $this->sell(['plot_number' => '7'])->assertStatus(422);

        $this->assertCount($before, $this->filesCreatedIn(public_path('profile_pic')));
    }

    public function test_different_plots_and_the_same_number_in_another_estate_are_allowed(): void
    {
        $this->sell(['plot_number' => '7'])->assertOk();
        $this->sell(['plot_number' => '8'])->assertOk();
        $this->sell(['plot_number' => '7', 'estate' => 'Nakibano'])->assertOk();

        $this->assertSame(3, DB::table('buyers')->count());
    }

    public function test_a_plot_that_went_back_on_the_market_can_be_sold_again(): void
    {
        // Resale/shift leaves the old buyer row in place, flagged back_on_market_status = 1.
        $this->makeBuyer(['plot_number' => '7', 'back_on_market_status' => 1]);

        $this->sell(['plot_number' => '7', 'firstname' => 'NewOwner'])->assertOk();

        $this->assertDatabaseHas('buyers', ['firstname' => 'NewOwner', 'plot_number' => '7']);
    }

    public function test_a_plot_with_an_active_buyer_is_blocked_even_if_an_old_resold_row_exists(): void
    {
        $this->makeBuyer(['plot_number' => '7', 'back_on_market_status' => 1]);
        $this->makeBuyer(['plot_number' => '7', 'back_on_market_status' => 0, 'shifted_to_status' => 2]);

        $this->sell(['plot_number' => '7'])->assertStatus(422);
    }

    public function test_two_buyers_may_share_a_half_plot_but_not_three(): void
    {
        $this->sell(['plot_number' => '5h', 'half_or_full' => '1'])->assertOk();
        $this->sell(['plot_number' => '5h', 'half_or_full' => '1'])->assertOk();
        $this->sell(['plot_number' => '5h', 'half_or_full' => '1'])->assertStatus(422);

        $this->assertSame(2, DB::table('buyers')->where('plot_number', '5h')->count());
    }

    public function test_a_full_plot_and_a_half_plot_sale_exclude_each_other(): void
    {
        $this->sell(['plot_number' => '1', 'half_or_full' => '0'])->assertOk();
        $this->sell(['plot_number' => '1', 'half_or_full' => '1'])->assertStatus(422);

        $this->sell(['plot_number' => '2', 'half_or_full' => '1'])->assertOk();
        $this->sell(['plot_number' => '2', 'half_or_full' => '0'])->assertStatus(422);
    }

    public function test_a_batch_containing_one_taken_plot_is_rejected_whole(): void
    {
        $this->makeBuyer(['plot_number' => '2']);
        $dims = ['width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'];

        $this->sell([
            'firstname' => 'Batch',
            'multiple_estates' => json_encode([
                ['estate' => 'Kayabwe', 'plot_number' => '1', 'location' => 'Mukono'] + $dims,
                ['estate' => 'Kayabwe', 'plot_number' => '2', 'location' => 'Mukono'] + $dims,
            ]),
        ])->assertStatus(422);

        $this->assertDatabaseMissing('buyers', ['firstname' => 'Batch']);
    }

    public function test_the_same_plot_twice_in_one_batch_is_rejected(): void
    {
        $dims = ['width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'];

        $this->sell([
            'firstname' => 'Dup',
            'multiple_estates' => json_encode([
                ['estate' => 'Kayabwe', 'plot_number' => '3', 'location' => 'Mukono'] + $dims,
                ['estate' => 'Kayabwe', 'plot_number' => '3', 'location' => 'Mukono'] + $dims,
            ]),
        ])->assertStatus(422);

        $this->assertDatabaseMissing('buyers', ['firstname' => 'Dup']);
    }

    public function test_deleting_a_sale_makes_the_plot_sellable_again(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $this->sell(['plot_number' => '7'])->assertOk();
        $id = DB::table('buyers')->where('plot_number', '7')->value('id');
        $this->makePlot(['plot_number' => '7', 'status' => 'Partially payed', 'buyer_id' => (string) $id]);

        $this->actingAsAdmin($super)->post("/delete/{$id}/7/Kayabwe")->assertSessionHas('success');

        $this->sell(['plot_number' => '7', 'firstname' => 'Again'])->assertOk();
    }
}
