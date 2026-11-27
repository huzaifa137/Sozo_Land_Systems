<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\Support\BuildsSozoData;
use Tests\Support\CleansUploads;
use Tests\TestCase;

class SalesModuleTest extends TestCase
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

    // ---------- recording a sale ----------

    public function test_a_single_sale_is_recorded(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->postJson('/store-buyer-details', $this->salePayload($this->salePhotos()))
            ->assertOk()->assertJson(['status' => true]);

        $this->assertDatabaseHas('buyers', [
            'firstname' => 'John', 'estate' => 'Kayabwe', 'plot_number' => '7', 'amount_payed' => 1000000,
        ]);
    }

    public function test_a_multi_plot_purchase_shares_one_group_id(): void
    {
        $admin = $this->makeAdmin();
        $estates = json_encode([
            ['estate' => 'Kayabwe', 'plot_number' => '1', 'location' => 'Mukono', 'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'],
            ['estate' => 'Kayabwe', 'plot_number' => '2', 'location' => 'Mukono', 'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'],
            ['estate' => 'Nakibano', 'plot_number' => '9', 'location' => 'Wakiso', 'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45'],
        ]);

        $this->actingAsAdmin($admin)
            ->postJson('/store-buyer-details', $this->salePayload(['multiple_estates' => $estates] + $this->salePhotos()))
            ->assertJson(['status' => true]);

        $rows = DB::table('buyers')->where('firstname', 'John')->get();
        $this->assertCount(3, $rows);
        $this->assertCount(1, $rows->pluck('multiple_user_id')->unique());
        $this->assertEqualsCanonicalizing(['1', '2', '9'], $rows->pluck('plot_number')->all());
    }

    public function test_uploaded_id_photos_are_stored_under_server_generated_names(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->postJson('/store-buyer-details', $this->salePayload([
            'national_id_front' => UploadedFile::fake()->image('../../my front id.png'),
            'national_id_back' => UploadedFile::fake()->image('back.jpg'),
            'profile_pic' => UploadedFile::fake()->image('me.png'),
        ]))->assertOk();

        $buyer = DB::table('buyers')->where('firstname', 'John')->first();

        foreach (['national_id_front', 'national_id_back', 'profile_pic'] as $col) {
            $this->assertMatchesRegularExpression('/^\d{12}_[A-Za-z0-9]{8}\.(png|jpg|jpeg)$/', $buyer->$col, $col);
        }
        $this->assertCount(2, $this->filesCreatedIn(public_path('public/national_id')));
        $this->assertCount(1, $this->filesCreatedIn(public_path('profile_pic')));
    }

    /** A real file on disk, so MIME detection sniffs the content instead of trusting the name. */
    private function realUpload(string $clientName, string $contents): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'sozo');
        file_put_contents($tmp, $contents);

        return new UploadedFile($tmp, $clientName, null, null, true);
    }

    public function test_executable_uploads_are_rejected_and_nothing_is_stored(): void
    {
        $admin = $this->makeAdmin();

        $attempts = [
            'php extension' => UploadedFile::fake()->create('shell.php', 1, 'application/x-php'),
            'phtml' => UploadedFile::fake()->create('shell.phtml', 1, 'text/html'),
            'php source renamed .jpg' => $this->realUpload('evil.jpg', '<?php system($_GET["c"]); ?>'),
            'php source renamed .png' => $this->realUpload('evil.png', "<?php echo 1;"),
            'html/js renamed .jpg' => $this->realUpload('x.jpg', '<script>alert(1)</script>'),
        ];

        foreach ($attempts as $label => $file) {
            $this->actingAsAdmin($admin)
                ->postJson('/store-buyer-details', $this->salePayload(['profile_pic' => $file] + $this->salePhotos()))
                ->assertStatus(422, "$label should be rejected");
        }

        $this->assertDatabaseCount('buyers', 0);
        $this->assertSame([], $this->filesCreatedIn(public_path('profile_pic')));
    }

    public function test_a_polyglot_image_can_never_be_stored_with_an_executable_extension(): void
    {
        $admin = $this->makeAdmin();
        // Genuine JPEG header with PHP appended: passes content sniffing, so the defence is the forced extension.
        $polyglot = $this->realUpload('shell.php.jpg', "\xFF\xD8\xFF\xE0" . str_repeat("\0", 16) . '<?php system($_GET["c"]); ?>');

        $this->actingAsAdmin($admin)
            ->postJson('/store-buyer-details', $this->salePayload(['profile_pic' => $polyglot] + $this->salePhotos()))
            ->assertOk();

        foreach ($this->filesCreatedIn(public_path('profile_pic')) as $stored) {
            $this->assertMatchesRegularExpression('/\.(jpg|jpeg|png|webp)$/', $stored);
            $this->assertStringNotContainsString('php', strtolower($stored));
        }
    }

    public function test_a_sale_without_id_photos_is_a_validation_error_not_a_database_crash(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->postJson('/store-buyer-details', $this->salePayload())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['national_id_front', 'national_id_back', 'profile_pic']);

        $this->assertDatabaseCount('buyers', 0);
    }

    // ---------- deleting a sale ----------

    public function test_super_admin_deleting_a_sale_returns_the_plot_to_the_market(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $buyer = $this->makeBuyer(['plot_number' => '1']);
        $plot = $this->makePlot(['plot_number' => '1', 'status' => 'Partially payed', 'buyer_id' => (string) $buyer]);

        $this->actingAsAdmin($super)->post("/delete/{$buyer}/1/Kayabwe")->assertSessionHas('success');

        $this->assertDatabaseMissing('buyers', ['id' => $buyer]);
        $this->assertDatabaseHas('plots', ['id' => $plot, 'status' => 'Not taken', 'buyer_id' => 0]);
    }

    public function test_plain_admin_cannot_delete_a_sale(): void
    {
        $admin = $this->makeAdmin('Admin');
        $buyer = $this->makeBuyer();
        $this->makePlot();

        $this->actingAsAdmin($admin)->post("/delete/{$buyer}/1/Kayabwe")->assertSessionHas('error');

        $this->assertDatabaseHas('buyers', ['id' => $buyer]);
    }

    public function test_deleting_a_sale_whose_plot_row_is_missing_still_works_instead_of_crashing(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $buyer = $this->makeBuyer(['plot_number' => '42']);

        $this->actingAsAdmin($super)->post("/delete/{$buyer}/42/Kayabwe")->assertSessionHas('success');

        $this->assertDatabaseMissing('buyers', ['id' => $buyer]);
    }

    public function test_a_sale_cannot_be_deleted_with_a_plain_get_link(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $buyer = $this->makeBuyer();
        $this->makePlot();

        $this->actingAsAdmin($super)->get("/delete/{$buyer}/1/Kayabwe")->assertStatus(405);

        $this->assertDatabaseHas('buyers', ['id' => $buyer]);
    }

    public function test_deleting_a_missing_sale_is_a_404(): void
    {
        $super = $this->makeAdmin('SuperAdmin');

        $this->actingAsAdmin($super)->post('/delete/99999/1/Kayabwe')->assertNotFound();
    }

    // ---------- old receipt upload ----------

    public function test_old_receipt_upload_records_the_receipt_and_adds_to_amount_paid(): void
    {
        $admin = $this->makeAdmin();
        $buyer = $this->makeBuyer(['amount_payed' => 1000000]);

        $this->actingAsAdmin($admin)->post('/receipt/upload', [
            'receipt' => UploadedFile::fake()->create('scan.pdf', 20, 'application/pdf'),
            'item_id' => $buyer, 'amount_paid' => 250000, 'balance' => 3750000,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('oldreceipts', ['buyer_id' => $buyer, 'amount_paid' => 250000]);
        $this->assertSame(1250000, (int) DB::table('buyers')->where('id', $buyer)->value('amount_payed'));
        $this->assertCount(1, $this->filesCreatedIn(public_path('storage/receipts')));
    }

    public function test_two_old_receipts_accumulate_correctly(): void
    {
        $admin = $this->makeAdmin();
        $buyer = $this->makeBuyer(['amount_payed' => 0]);

        foreach ([100000, 200000] as $amt) {
            $this->actingAsAdmin($admin)->post('/receipt/upload', [
                'receipt' => UploadedFile::fake()->create("r$amt.pdf", 5, 'application/pdf'),
                'item_id' => $buyer, 'amount_paid' => $amt, 'balance' => 0,
            ]);
        }

        $this->assertSame(300000, (int) DB::table('buyers')->where('id', $buyer)->value('amount_payed'));
    }

    public function test_old_receipt_upload_rejects_bad_input(): void
    {
        $admin = $this->makeAdmin();
        $buyer = $this->makeBuyer();
        $good = ['item_id' => $buyer, 'amount_paid' => 1000, 'balance' => 0];

        $cases = [
            'php file' => [['receipt' => UploadedFile::fake()->create('x.php', 1, 'application/x-php')] + $good, 'receipt'],
            'no file' => [$good, 'receipt'],
            'unknown buyer' => [['receipt' => UploadedFile::fake()->create('a.pdf', 1, 'application/pdf'), 'item_id' => 99999] + $good, 'item_id'],
            'negative amount' => [['receipt' => UploadedFile::fake()->create('a.pdf', 1, 'application/pdf'), 'amount_paid' => -5] + $good, 'amount_paid'],
            'non-numeric amount' => [['receipt' => UploadedFile::fake()->create('a.pdf', 1, 'application/pdf'), 'amount_paid' => 'lots'] + $good, 'amount_paid'],
        ];

        foreach ($cases as $label => [$payload, $errorField]) {
            $this->actingAsAdmin($admin)->post('/receipt/upload', $payload)->assertSessionHasErrors($errorField);
        }

        $this->assertDatabaseCount('oldreceipts', 0);
        $this->assertSame(1000000, (int) DB::table('buyers')->where('id', $buyer)->value('amount_payed'));
    }

    // ---------- receipt PDF download ----------

    public function test_receipt_download_is_404_for_a_missing_file_and_serves_an_existing_one(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAsAdmin($admin)->get('/download_receipt/nope.pdf')->assertNotFound();

        $dir = storage_path('app/public/pdf_receipts');
        @mkdir($dir, 0755, true);
        file_put_contents($dir . '/unit_receipt.pdf', '%PDF-1.4');

        $this->actingAsAdmin($admin)->get('/download_receipt/unit_receipt.pdf')->assertOk()
            ->assertHeader('content-disposition');
    }

    // ---------- dashboard ----------

    public function test_dashboard_lists_recent_customers_and_the_all_buyers_link_works(): void
    {
        $admin = $this->makeAdmin();
        $this->makeBuyer(['firstname' => 'Zawadi']);

        $this->actingAsAdmin($admin)->get('/admin-dashboard')->assertOk()->assertSee('Zawadi');
        $this->actingAsAdmin($admin)->get('/all-buyers')->assertRedirect('all-sales');
        $this->actingAsAdmin($admin)->get('/all-sales')->assertOk();
    }

    public function test_estate_file_endpoint_serves_inline_or_as_download_and_404s_otherwise(): void
    {
        $dir = public_path('estate_pdf');
        $existed = is_dir($dir);
        @mkdir($dir, 0755, true);
        file_put_contents($dir . '/unit_plan.pdf', '%PDF-1.4');
        $id = $this->makeEstate(['estate_pdf' => 'unit_plan.pdf']);
        $noFile = $this->makeEstate(['estate_name' => 'Empty', 'estate_pdf' => '']);

        try {
            $this->get("/estate-file/{$id}")->assertOk()
                ->assertHeader('content-disposition', 'inline; filename="unit_plan.pdf"');
            $this->get("/estate-file/{$id}?download=1")
                ->assertHeader('content-disposition', 'attachment; filename="unit_plan.pdf"');
            $this->get("/estate-file/{$noFile}")->assertNotFound();
            $this->get('/estate-file/99999')->assertNotFound();
        } finally {
            @unlink($dir . '/unit_plan.pdf');
            if (!$existed) {
                @rmdir($dir);
            }
        }
    }
}
