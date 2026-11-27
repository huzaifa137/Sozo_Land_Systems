<?php

namespace Tests\Support;

use App\Models\AdminRegister;
use Illuminate\Support\Facades\DB;

/**
 * Builders for the tables this app writes with raw DB calls (the models have no factories).
 */
trait BuildsSozoData
{
    protected function makeAdmin(string $role = 'Admin', array $overrides = []): AdminRegister
    {
        static $n = 0;
        $n++;

        return AdminRegister::forceCreate(array_merge([
            'username' => "user{$n}",
            'firstname' => 'First' . $n,
            'lastname' => 'Last' . $n,
            'email' => "admin{$n}@sozo.test",
            'password' => bcrypt('password123'),
            'admin_category' => $role,
            'added_by' => '0',
        ], $overrides));
    }

    /** Make the next requests come from this logged-in admin. */
    protected function actingAsAdmin(AdminRegister $admin): static
    {
        return $this->withSession(['LoggedAdmin' => $admin->id]);
    }

    protected function makeEstate(array $overrides = []): int
    {
        return DB::table('estates')->insertGetId(array_merge([
            'estate_name' => 'Kayabwe',
            'estate_price' => '5000000',
            'location' => 'Mukono',
            'number_of_plots' => '10',
            'estate_pdf' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    protected function makePlot(array $overrides = []): int
    {
        return DB::table('plots')->insertGetId(array_merge([
            'estate' => 'Kayabwe',
            'plot_number' => '1',
            'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45',
            'status' => 'Not taken',
            'location' => 'Mukono',
            'half_or_full' => '0',
            'buyer_id' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    protected function makeBuyer(array $overrides = []): int
    {
        return DB::table('buyers')->insertGetId(array_merge([
            'firstname' => 'Jane', 'lastname' => 'Doe', 'gender' => 'F',
            'date_of_birth' => '1990-01-01', 'NIN' => 'CM123', 'national_id_front' => 'f.jpg',
            'national_id_back' => 'b.jpg', 'profile_pic' => 'p.jpg', 'card_number' => '123',
            'land_poster' => '0', 'phonenumber' => '0700000000', 'method_payment' => 'cash',
            'purchase_type' => 'Installments', 'estate' => 'Kayabwe', 'location' => 'Mukono',
            'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45',
            'plot_number' => '1', 'amount_payed' => 1000000, 'balance' => 4000000,
            'date_sold' => '2025/01/01', 'next_installment_pay' => '2025-02-01',
            'reciepts' => 'Pending', 'agreement' => 'Pending', 'half_or_full' => '0',
            'added_by' => 'tester', 'created_at' => now(), 'updated_at' => now(),
        ], $overrides));
    }

    /** The three ID/profile photos the sale form must send. */
    protected function salePhotos(): array
    {
        return [
            'national_id_front' => \Illuminate\Http\UploadedFile::fake()->image('front.png'),
            'national_id_back' => \Illuminate\Http\UploadedFile::fake()->image('back.png'),
            'profile_pic' => \Illuminate\Http\UploadedFile::fake()->image('me.png'),
        ];
    }

    /** The fields the "record a sale" form posts. */
    protected function salePayload(array $overrides = []): array
    {
        return array_merge([
            'firstname' => 'John', 'lastname' => 'Okello', 'gender' => 'M',
            'date_of_birth' => '1985-05-05', 'NIN' => 'CM999', 'card_number' => '555',
            'land_poster' => '0', 'phonenumber' => '0701234567', 'payment_method' => 'cash',
            'purchase_type' => 'Installments', 'estate' => 'Kayabwe', 'location' => 'Mukono',
            'width_1' => '25', 'width_2' => '25', 'height_1' => '45', 'height_2' => '45',
            'plot_number' => '7', 'amount_payed' => 1000000, 'balance' => 4000000,
            'half_or_full' => '0', 'hidden_user_name' => 'tester',
        ], $overrides);
    }
}
