<?php

namespace Tests\Feature;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Tests\Support\BuildsSozoData;
use Tests\Support\CleansUploads;
use Tests\TestCase;

class ReceiptTest extends TestCase
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

    private function payload(int $buyer, array $o = []): array
    {
        return array_merge([
            'user_id' => $buyer, 'user_name' => 'Jane Doe', 'user_email' => 'jane@example.com',
            'amount_paid' => '500,000', 'balance_pending' => '3,500,000',
            'Date_of_payment' => '2026-11-26', 'phone_number' => '0700000000',
            'amount_in_words' => 'five hundred thousand',
        ], $o);
    }

    private function issue(int $buyer, array $o = [])
    {
        return $this->actingAsAdmin($this->makeAdmin())->post('/store-new-receipt', $this->payload($buyer, $o));
    }

    private function buyerRow(int $id): object
    {
        return DB::table('buyers')->find($id);
    }

    // ===== happy path =====

    public function test_issuing_a_receipt_records_it_writes_the_pdf_and_updates_the_buyer(): void
    {
        $buyer = $this->makeBuyer(['amount_payed' => 1000000, 'balance' => 4000000]);

        $resp = $this->issue($buyer);

        $resp->assertSessionHas('download_link');
        $row = DB::table('reciepts')->where('user_id', (string) $buyer)->first();
        $this->assertNotNull($row);
        $this->assertSame('500,000', $row->Amount);
        $this->assertSame('3,500,000', $row->Balance);
        $this->assertNotSame('-', $row->reciept);

        $this->assertFileExists(storage_path("app/public/pdf_receipts/{$row->reciept}"));
        $this->assertFileExists(public_path("storage/pdf_receipts/{$row->reciept}"));
        $this->assertStringStartsWith('%PDF', file_get_contents(storage_path("app/public/pdf_receipts/{$row->reciept}")));

        $b = $this->buyerRow($buyer);
        $this->assertSame(1500000, (int) $b->amount_payed);
        $this->assertSame(3500000, (int) $b->balance);
    }

    public function test_the_first_receipt_works_on_a_fresh_install_where_the_pdf_folders_do_not_exist(): void
    {
        foreach ([storage_path('app/public/pdf_receipts'), public_path('storage/pdf_receipts')] as $dir) {
            $this->assertDirectoryDoesNotExist($dir, 'precondition: fresh install');
        }
        $buyer = $this->makeBuyer();

        $this->issue($buyer)->assertSessionHas('download_link');

        $this->assertCount(1, $this->filesCreatedIn(storage_path('app/public/pdf_receipts')));
    }

    public function test_the_download_link_points_at_a_file_that_exists(): void
    {
        $buyer = $this->makeBuyer();
        $link = $this->issue($buyer)->baseResponse->getSession()->get('download_link');

        $file = basename(parse_url($link, PHP_URL_PATH));
        $this->actingAsAdmin($this->makeAdmin())->get("/download_receipt/{$file}")->assertOk();
    }

    // ===== receipt numbers =====

    public function test_receipt_numbers_are_unique_increasing_and_above_the_legacy_range(): void
    {
        $buyer = $this->makeBuyer(['balance' => 9000000]);
        $numbers = [];
        View::creator('invoice_pdf', function ($view) use (&$numbers) {
            $numbers[] = $view->getData()['receipt_no'];
        });

        for ($i = 0; $i < 6; $i++) {
            $this->issue($buyer, ['balance_pending' => '8,000,000']);
        }

        $this->assertCount(6, $numbers);
        $this->assertCount(6, array_unique($numbers), 'every receipt has its own number');
        $this->assertSame($numbers, array_values(collect($numbers)->sort()->all()), 'numbers increase');
        $this->assertGreaterThan(50000, min($numbers), 'never collides with the old random 10000-50000 numbers');
    }

    public function test_receipts_issued_in_the_same_second_do_not_overwrite_each_others_pdf(): void
    {
        $buyer = $this->makeBuyer();
        $this->issue($buyer);
        $this->issue($buyer);

        $files = DB::table('reciepts')->pluck('reciept');
        $this->assertCount(2, $files->unique());
        $this->assertCount(2, $this->filesCreatedIn(storage_path('app/public/pdf_receipts')));
    }

    // ===== validation =====

    public function test_bad_amounts_and_balances_are_rejected_and_nothing_changes(): void
    {
        $buyer = $this->makeBuyer(['amount_payed' => 1000000, 'balance' => 4000000]);

        $bad = [
            'text amount' => [['amount_paid' => 'lots'], 'amount_paid'],
            'zero amount' => [['amount_paid' => '0'], 'amount_paid'],
            'negative amount' => [['amount_paid' => '-500'], 'amount_paid'],
            'text balance' => [['balance_pending' => 'abc'], 'balance_pending'],
            'negative balance' => [['balance_pending' => '-1'], 'balance_pending'],
            'unknown buyer' => [['user_id' => 99999], 'user_id'],
            'missing phone' => [['phone_number' => ''], 'phone_number'],
        ];

        foreach ($bad as $label => [$override, $field]) {
            $this->issue($buyer, $override)->assertSessionHasErrors($field);
        }

        $this->assertDatabaseCount('reciepts', 0);
        $b = $this->buyerRow($buyer);
        $this->assertSame(1000000, (int) $b->amount_payed);
        $this->assertSame(4000000, (int) $b->balance);
        $this->assertSame([], $this->filesCreatedIn(storage_path('app/public/pdf_receipts')));
    }

    public function test_comma_formatted_amounts_are_accepted(): void
    {
        $buyer = $this->makeBuyer(['amount_payed' => 0, 'balance' => 2000000]);

        $this->issue($buyer, ['amount_paid' => '1,250,000', 'balance_pending' => '750,000'])->assertSessionHasNoErrors()->assertSessionHas('download_link');

        $this->assertSame(1250000, (int) $this->buyerRow($buyer)->amount_payed);
    }

    // ===== balance: audited by default, enforceable on demand =====

    public function test_a_hand_typed_balance_that_differs_is_still_accepted_but_logged(): void
    {
        Log::spy();
        $buyer = $this->makeBuyer(['balance' => 4000000]);

        // e.g. a discount was agreed: 4,000,000 - 500,000 would be 3,500,000
        $this->issue($buyer, ['balance_pending' => '3,000,000'])->assertSessionHasNoErrors()->assertSessionHas('download_link');

        $this->assertSame(3000000, (int) $this->buyerRow($buyer)->balance);
        Log::shouldHaveReceived('warning')->withArgs(fn ($msg, $ctx) => $msg === 'Receipt balance differs from computed balance'
            && $ctx['expected_balance'] === 3500000 && $ctx['posted_balance'] === 3000000)->once();
    }

    public function test_a_matching_balance_is_not_logged(): void
    {
        Log::spy();
        $buyer = $this->makeBuyer(['balance' => 4000000]);

        $this->issue($buyer)->assertSessionHasNoErrors()->assertSessionHas('download_link');

        Log::shouldNotHaveReceived('warning');
    }

    public function test_strict_mode_rejects_a_wrong_balance_and_accepts_the_right_one(): void
    {
        config(['sozo.strict_receipt_balance' => true]);
        $buyer = $this->makeBuyer(['amount_payed' => 1000000, 'balance' => 4000000]);

        $this->issue($buyer, ['balance_pending' => '0'])->assertSessionHasErrors('balance_pending');
        $this->assertSame(1000000, (int) $this->buyerRow($buyer)->amount_payed);

        $this->issue($buyer, ['balance_pending' => '3,500,000'])->assertSessionHasNoErrors()->assertSessionHas('download_link');
        $this->assertSame(1500000, (int) $this->buyerRow($buyer)->amount_payed);
    }

    public function test_strict_mode_never_expects_a_negative_balance_on_an_overpayment(): void
    {
        config(['sozo.strict_receipt_balance' => true]);
        $buyer = $this->makeBuyer(['balance' => 100000]);

        $this->issue($buyer, ['amount_paid' => '150,000', 'balance_pending' => '0'])->assertSessionHasNoErrors()->assertSessionHas('download_link');
    }

    public function test_a_receipt_can_be_issued_when_the_previous_balance_is_already_zero(): void
    {
        // A zero previous balance must never block a receipt (balance column is an integer).
        $buyer = $this->makeBuyer(['balance' => 0]);
        DB::table('buyers')->where('id', $buyer)->update(['balance' => 0]);

        $this->issue($buyer, ['balance_pending' => '123,000'])->assertSessionHasNoErrors()->assertSessionHas('download_link');
    }

    // ===== atomicity =====

    public function test_if_the_pdf_cannot_be_generated_nothing_is_recorded(): void
    {
        $buyer = $this->makeBuyer(['amount_payed' => 1000000, 'balance' => 4000000]);
        Pdf::shouldReceive('loadView')->andThrow(new \RuntimeException('dompdf exploded'));

        try {
            $this->withoutExceptionHandling()->issue($buyer);
            $this->fail('expected the PDF failure to surface');
        } catch (\RuntimeException $e) {
            $this->assertSame('dompdf exploded', $e->getMessage());
        }

        $this->assertDatabaseCount('reciepts', 0);
        $b = $this->buyerRow($buyer);
        $this->assertSame(1000000, (int) $b->amount_payed);
        $this->assertSame(4000000, (int) $b->balance);
    }

    public function test_guests_cannot_issue_receipts(): void
    {
        $buyer = $this->makeBuyer();

        $this->post('/store-new-receipt', $this->payload($buyer))->assertRedirect('login');
        $this->assertDatabaseCount('reciepts', 0);
    }
}
