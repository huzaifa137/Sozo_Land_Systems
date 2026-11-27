<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The two migrations that used to live in migrations/ignored/ describe objects production already has.
 * Running them against such a database must be a harmless no-op.
 */
class MigrationSafetyTest extends TestCase
{
    use RefreshDatabase;

    private const FILES = [
        'database/migrations/2025_10_03_123153_add_request_permission_to_buyers_table.php',
        'database/migrations/2025_10_06_125912_create_oldreceipts_table.php',
    ];

    public function test_a_fresh_install_gets_the_table_and_column_the_code_depends_on(): void
    {
        $this->assertTrue(Schema::hasTable('oldreceipts'));
        $this->assertTrue(Schema::hasColumn('buyers', 'request_permission'));
        $this->assertTrue(Schema::hasColumns('oldreceipts', ['buyer_id', 'file_path', 'amount_paid', 'balance']));
    }

    public function test_running_them_again_on_a_database_that_already_has_them_changes_nothing(): void
    {
        \DB::table('buyers')->insert(['id' => 1] + $this->minimalBuyer());
        \DB::table('oldreceipts')->insert(['buyer_id' => 1, 'file_path' => 'receipts/a.pdf', 'amount_paid' => 5, 'balance' => 0]);

        foreach (self::FILES as $file) {
            (require base_path($file))->up();   // must not throw "already exists"
        }

        $this->assertSame(1, \DB::table('oldreceipts')->count(), 'existing data is untouched');
        $this->assertSame(1, \DB::table('buyers')->count());
    }

    public function test_the_ignored_folder_no_longer_hides_migrations(): void
    {
        $this->assertDirectoryDoesNotExist(base_path('database/migrations/ignored'));
    }

    private function minimalBuyer(): array
    {
        return [
            'firstname' => 'a', 'lastname' => 'b', 'gender' => 'F', 'date_of_birth' => 'x', 'NIN' => 'x',
            'national_id_front' => 'x', 'national_id_back' => 'x', 'profile_pic' => 'x', 'card_number' => 'x',
            'land_poster' => 'x', 'phonenumber' => 'x', 'method_payment' => 'x', 'purchase_type' => 'x',
            'estate' => 'x', 'location' => 'x', 'width_1' => '1', 'width_2' => '1', 'height_1' => '1', 'height_2' => '1',
            'plot_number' => '1', 'amount_payed' => 0, 'balance' => 0, 'date_sold' => 'x', 'next_installment_pay' => 'x',
            'reciepts' => '0', 'agreement' => 'x', 'half_or_full' => '0', 'added_by' => 'x',
        ];
    }
}
