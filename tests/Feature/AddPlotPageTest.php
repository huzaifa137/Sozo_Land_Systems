<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\Support\BuildsSozoData;
use Tests\Support\CleansUploads;
use Tests\TestCase;

/**
 * The "Add Plot" page (/plots). It posts to /send-plot-estate; /send-plot-data belongs to the backup importer.
 */
class AddPlotPageTest extends TestCase
{
    use RefreshDatabase, BuildsSozoData, CleansUploads;

    protected function setUp(): void
    {
        parent::setUp();
        $this->snapshotUploads();
        $this->makeEstate(['estate_name' => 'Kayabwe', 'estate_price' => '5000000', 'number_of_plots' => '10']);
    }

    protected function tearDown(): void
    {
        $this->cleanUploads();
        parent::tearDown();
    }

    /** The fields plots.blade.php appends to its FormData. */
    private function pagePayload(array $o = []): array
    {
        return array_merge([
            'land_status' => 'Not_taken', 'House_plot' => 'Plot', 'Estate' => 'Kayabwe', 'location' => 'Mukono',
            'width1' => '25', 'width2' => '25', 'height1' => '45', 'height2' => '45', 'plot_number' => '11',
            'firstname' => '-', 'lastname' => '-', 'phonenumber' => '-', 'amount_paid' => '-', 'date_sold' => '-',
            'balance' => '-', 'next_installment_date' => '', 'exceptional_status' => 'No', 'exceptional_amount' => '',
            'hidden_user_naeme' => 'tester',
        ], $o);
    }

    private function buyerDocs(): array
    {
        return [
            'national_id_front' => UploadedFile::fake()->image('front.png'),
            'national_id_back' => UploadedFile::fake()->image('back.png'),
            'profile_pic' => UploadedFile::fake()->image('me.png'),
        ];
    }

    private function submit(array $payload)
    {
        return $this->actingAsAdmin($this->makeAdmin())->postJson('/send-plot-estate', $payload);
    }

    // ===== routing =====

    public function test_the_page_posts_to_the_plot_endpoint_and_not_the_backup_importer(): void
    {
        $html = $this->actingAsAdmin($this->makeAdmin())->get('/plots')->assertOk()->getContent();

        $this->assertStringContainsString("url: '/send-plot-estate'", $html);
        $this->assertStringNotContainsString("url: '/send-plot-data'", $html);
    }

    public function test_the_backup_importer_still_answers_on_its_own_url(): void
    {
        // Empty payload reaches sendPlotData's own validation (it needs estate + records[]).
        $this->actingAsAdmin($this->makeAdmin())->postJson('/send-plot-data', [])
            ->assertStatus(422)->assertJsonValidationErrors(['estate', 'records']);
    }

    public function test_guests_cannot_add_plots(): void
    {
        $this->postJson('/send-plot-estate', $this->pagePayload())->assertStatus(302);
        $this->assertDatabaseCount('plots', 0);
    }

    // ===== Not taken (plot only) =====

    public function test_adding_an_unsold_plot_creates_just_the_plot(): void
    {
        // The page sends the literal string "undefined" for file inputs it did not use.
        $this->submit($this->pagePayload(['national_id_front' => 'undefined', 'profile_pic' => 'undefined']))
            ->assertOk()->assertJson(['status' => true]);

        $this->assertDatabaseHas('plots', ['estate' => 'Kayabwe', 'plot_number' => '11', 'status' => 'Not taken', 'buyer_id' => '0']);
        $this->assertDatabaseCount('buyers', 0);
    }

    public function test_an_exceptional_price_plot_stores_its_amount(): void
    {
        $this->submit($this->pagePayload(['exceptional_status' => 'Yes', 'exceptional_amount' => '6000000']))
            ->assertJson(['status' => true]);

        $this->assertDatabaseHas('plots', ['plot_number' => '11', 'exceptional_status' => 'Yes', 'exceptional_amount' => '6000000']);
    }

    public function test_an_exceptional_price_below_the_estate_price_is_refused(): void
    {
        $this->submit($this->pagePayload(['exceptional_status' => 'Yes', 'exceptional_amount' => '1000']))
            ->assertJson(['status' => false, 'message' => 'Exceptional Amount entered is less than original estate price']);

        $this->assertDatabaseCount('plots', 0);
    }

    // ===== Under payment (plot + buyer) =====

    public function test_adding_a_plot_under_payment_creates_buyer_and_plot_and_links_them(): void
    {
        $this->submit($this->pagePayload($this->buyerDocs() + [
            'land_status' => 'Under_payment', 'firstname' => 'Amina', 'lastname' => 'Nabirye', 'phonenumber' => '0700111222',
            'amount_paid' => '1000000', 'balance' => '4000000', 'date_sold' => '2026-10-01', 'next_installment_date' => '2026-12-15',
        ]))->assertOk()->assertJson(['status' => true]);

        $buyer = DB::table('buyers')->where('firstname', 'Amina')->first();
        $this->assertNotNull($buyer);
        $this->assertSame('2026-12-15', $buyer->next_installment_pay);
        $this->assertSame(1000000, (int) $buyer->amount_payed);
        $this->assertSame(4000000, (int) $buyer->balance);
        $this->assertSame('tester', $buyer->added_by);
        $this->assertDatabaseHas('plots', ['plot_number' => '11', 'buyer_id' => (string) $buyer->id]);
    }

    public function test_the_buyers_documents_are_stored_under_safe_generated_names(): void
    {
        $this->submit($this->pagePayload($this->buyerDocs() + ['land_status' => 'Under_payment', 'firstname' => 'Amina',
            'amount_paid' => '1', 'balance' => '1', 'next_installment_date' => '2026-12-15']))->assertOk();

        $buyer = DB::table('buyers')->where('firstname', 'Amina')->first();
        foreach (['national_id_front', 'national_id_back', 'profile_pic'] as $col) {
            $this->assertMatchesRegularExpression('/^\d{12}_[A-Za-z0-9]{8}\.(png|jpg)$/', $buyer->$col, $col);
        }
    }

    public function test_an_unknown_admin_name_falls_back_to_the_logged_in_admin(): void
    {
        $admin = $this->makeAdmin('Admin', ['username' => 'sarah']);

        $this->actingAsAdmin($admin)->postJson('/send-plot-estate', $this->pagePayload($this->buyerDocs() + [
            'land_status' => 'Under_payment', 'firstname' => 'Amina', 'hidden_user_naeme' => '',
            'amount_paid' => '1', 'balance' => '1', 'next_installment_date' => '2026-12-15',
        ]))->assertJson(['status' => true]);

        $this->assertSame('sarah', DB::table('buyers')->where('firstname', 'Amina')->value('added_by'));
    }

    // ===== Fully taken (plot + buyer + agreement scans) =====

    public function test_adding_a_fully_paid_plot_records_buyer_plot_and_agreement_scans(): void
    {
        $this->submit($this->pagePayload($this->buyerDocs() + [
            'land_status' => 'Fully_taken', 'firstname' => 'Brian', 'lastname' => 'Okoth', 'phonenumber' => '0700',
            'amount_paid' => '5000000', 'balance' => '0', 'date_sold' => '2026-09-09',
            'files' => [UploadedFile::fake()->create('agreement1.pdf', 10, 'application/pdf'), UploadedFile::fake()->image('page2.png')],
        ]))->assertOk()->assertJson(['status' => true]);

        $buyer = DB::table('buyers')->where('firstname', 'Brian')->first();
        $this->assertSame('Fully payed', $buyer->next_installment_pay);
        $this->assertDatabaseHas('plots', ['plot_number' => '11', 'status' => 'Fully payed', 'buyer_id' => (string) $buyer->id]);
        $this->assertSame(2, DB::table('agreements')->where('user_id', $buyer->agreement)->count());
        $this->assertCount(2, $this->filesCreatedIn(public_path('agreements')));
    }

    public function test_a_fully_taken_plot_needs_an_agreement_attached(): void
    {
        $this->submit($this->pagePayload($this->buyerDocs() + ['land_status' => 'Fully_taken', 'firstname' => 'Brian', 'amount_paid' => '5000000']))
            ->assertJson(['status' => false, 'message' => 'Agreement has to be attached before submitting']);

        $this->assertDatabaseCount('buyers', 0);
        $this->assertDatabaseCount('plots', 0);
    }

    public function test_a_fully_taken_plot_cannot_be_paid_less_than_the_estate_price(): void
    {
        $this->submit($this->pagePayload($this->buyerDocs() + [
            'land_status' => 'Fully_taken', 'firstname' => 'Brian', 'amount_paid' => '100',
            'files' => [UploadedFile::fake()->create('a.pdf', 1, 'application/pdf')],
        ]))->assertJson(['status' => false, 'message' => 'Amount used to purchase this plot is less']);

        $this->assertDatabaseCount('buyers', 0);
    }

    // ===== availability rules =====

    public function test_a_plot_number_cannot_be_entered_twice(): void
    {
        $this->submit($this->pagePayload())->assertJson(['status' => true]);

        $this->submit($this->pagePayload())->assertJson(['status' => false, 'message' => 'Plot has already been entered before !']);

        $this->assertSame(1, DB::table('plots')->count());
    }

    public function test_a_plot_that_already_has_a_buyer_is_refused(): void
    {
        $this->makeBuyer(['plot_number' => '11']);

        $this->submit($this->pagePayload())->assertJson(['status' => false, 'message' => 'Plot being entered is already taken']);
    }

    public function test_an_estate_that_is_full_refuses_more_plots(): void
    {
        DB::table('estates')->where('estate_name', 'Kayabwe')->update(['number_of_plots' => '1']);
        $this->makeBuyer(['plot_number' => '1']);

        $this->submit($this->pagePayload())->assertJson(['status' => false, 'message' => 'The plots in this estate are fully taken']);
    }

    // ===== validation & upload safety =====

    public function test_an_invalid_status_estate_or_plot_number_is_a_validation_error(): void
    {
        $this->submit($this->pagePayload(['land_status' => 'Whatever']))->assertStatus(422)->assertJsonValidationErrors('land_status');
        $this->submit($this->pagePayload(['Estate' => '']))->assertStatus(422)->assertJsonValidationErrors('Estate');
        $this->submit($this->pagePayload(['plot_number' => '']))->assertStatus(422)->assertJsonValidationErrors('plot_number');
        $this->assertDatabaseCount('plots', 0);
    }

    public function test_a_sold_plot_needs_all_three_documents(): void
    {
        $this->submit($this->pagePayload(['land_status' => 'Under_payment', 'firstname' => 'X']))
            ->assertStatus(422)->assertJsonValidationErrors(['national_id_front', 'national_id_back', 'profile_pic']);
    }

    public function test_executable_documents_are_refused_and_nothing_is_saved(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'sozo');
        file_put_contents($tmp, '<?php system($_GET["c"]); ?>');
        $evil = new UploadedFile($tmp, 'evil.jpg', null, null, true);

        $docs = $this->buyerDocs();
        $this->submit($this->pagePayload(['land_status' => 'Under_payment', 'firstname' => 'X', 'profile_pic' => $evil] + $docs))
            ->assertStatus(422)->assertJsonValidationErrors('profile_pic');

        $this->submit($this->pagePayload(['land_status' => 'Fully_taken', 'firstname' => 'X', 'amount_paid' => '5000000',
            'files' => [UploadedFile::fake()->create('shell.php', 1, 'application/x-php')]] + $this->buyerDocs()))
            ->assertStatus(422);

        $this->assertDatabaseCount('buyers', 0);
        $this->assertDatabaseCount('plots', 0);
        $this->assertSame([], $this->filesCreatedIn(public_path('profile_pic')));
        $this->assertSame([], $this->filesCreatedIn(public_path('agreements')));
    }

    public function test_the_buyer_and_plot_are_saved_together_or_not_at_all(): void
    {
        // SQLite-only: MySQL would implicitly commit on CREATE TRIGGER and leak rows between tests.
        if (DB::getDriverName() !== 'sqlite') {
            $this->markTestSkipped('trigger-based failure injection is only safe on sqlite');
        }

        // Let the buyer insert succeed, then make the plot insert fail.
        DB::unprepared("CREATE TRIGGER fail_plot_insert BEFORE INSERT ON plots BEGIN SELECT RAISE(ABORT, 'plot insert failed'); END;");

        $payload = $this->pagePayload($this->buyerDocs() + [
            'land_status' => 'Under_payment', 'firstname' => 'Rollback', 'amount_paid' => '1', 'balance' => '1', 'next_installment_pay' => '2026-12-15',
            'next_installment_date' => '2026-12-15',
        ]);

        $this->withoutExceptionHandling();
        try {
            $this->submit($payload);
            $this->fail('the plot insert should have failed');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertStringContainsString('plot insert failed', $e->getMessage());
        }

        $this->assertDatabaseMissing('buyers', ['firstname' => 'Rollback']);
    }
}
