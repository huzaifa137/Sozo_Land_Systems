<?php

namespace Tests\Feature;

use App\Models\AdminRegister;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Support\BuildsSozoData;
use Tests\TestCase;

/**
 * Who may create / edit / delete admin accounts. These guard privilege escalation.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase, BuildsSozoData;

    private function newAdminPayload(array $o = []): array
    {
        return array_merge([
            'username' => 'newbie', 'firstname' => 'New', 'lastname' => 'Admin',
            'email' => 'newbie@sozo.test', 'admin_category' => 'Admin',
            'password' => 'longenough1', 'confirm_password' => 'longenough1',
        ], $o);
    }

    public function test_super_admin_can_create_an_admin_and_password_is_hashed(): void
    {
        $super = $this->makeAdmin('SuperAdmin');

        $this->actingAsAdmin($super)->post('/admin-registration', $this->newAdminPayload())
            ->assertSessionHas('success');

        $created = AdminRegister::where('email', 'newbie@sozo.test')->first();
        $this->assertNotNull($created);
        $this->assertNotSame('longenough1', $created->password);
        $this->assertTrue(Hash::check('longenough1', $created->password));
        $this->assertEquals($super->id, $created->added_by);
    }

    public function test_plain_admin_cannot_create_accounts(): void
    {
        $admin = $this->makeAdmin('Admin');

        $this->actingAsAdmin($admin)->post('/admin-registration', $this->newAdminPayload())
            ->assertRedirect(route('admin-dashboard'));

        $this->assertDatabaseMissing('admin_registers', ['email' => 'newbie@sozo.test']);
    }

    public function test_plain_admin_cannot_mint_a_super_admin(): void
    {
        $admin = $this->makeAdmin('Admin');

        $this->actingAsAdmin($admin)
            ->post('/admin-registration', $this->newAdminPayload(['admin_category' => 'SuperAdmin']));

        $this->assertDatabaseMissing('admin_registers', ['admin_category' => 'SuperAdmin', 'email' => 'newbie@sozo.test']);
    }

    public function test_plain_admin_cannot_promote_themselves(): void
    {
        $admin = $this->makeAdmin('Admin');

        $this->actingAsAdmin($admin)->post('/store-user-record', $this->newAdminPayload([
            'record_id' => $admin->id, 'email' => $admin->email, 'admin_category' => 'SuperAdmin',
        ]));

        $this->assertSame('Admin', $admin->fresh()->admin_category);
    }

    public function test_plain_admin_cannot_open_the_registration_or_edit_pages(): void
    {
        $admin = $this->makeAdmin('Admin');
        $other = $this->makeAdmin('Admin');

        $this->actingAsAdmin($admin)->get('/admin-register')->assertRedirect(route('admin-dashboard'));
        $this->actingAsAdmin($admin)->get("/edit-user/{$other->id}")->assertRedirect(route('admin-dashboard'));
    }

    public function test_role_must_be_one_of_the_known_roles(): void
    {
        $super = $this->makeAdmin('SuperAdmin');

        $this->actingAsAdmin($super)->post('/admin-registration', $this->newAdminPayload(['admin_category' => 'Root']))
            ->assertSessionHasErrors('admin_category');
    }

    public function test_duplicate_email_weak_password_and_mismatch_are_rejected(): void
    {
        $super = $this->makeAdmin('SuperAdmin', ['email' => 'taken@sozo.test']);

        $this->actingAsAdmin($super)->post('/admin-registration', $this->newAdminPayload(['email' => 'taken@sozo.test']))
            ->assertSessionHasErrors('email');

        $this->actingAsAdmin($super)->post('/admin-registration', $this->newAdminPayload(['password' => 'short', 'confirm_password' => 'short']))
            ->assertSessionHasErrors('password');

        $this->actingAsAdmin($super)->post('/admin-registration', $this->newAdminPayload(['confirm_password' => 'different123']))
            ->assertSessionHas('fail');

        $this->assertDatabaseMissing('admin_registers', ['email' => 'newbie@sozo.test']);
    }

    public function test_super_admin_can_edit_another_admin(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $target = $this->makeAdmin('Admin');

        $this->actingAsAdmin($super)->post('/store-user-record', $this->newAdminPayload([
            'record_id' => $target->id, 'email' => 'changed@sozo.test', 'username' => 'renamed',
        ]))->assertRedirect('user-information');

        $this->assertSame('changed@sozo.test', $target->fresh()->email);
    }

    public function test_updating_a_user_may_keep_their_own_email_but_not_take_someone_elses(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $a = $this->makeAdmin('Admin');
        $b = $this->makeAdmin('Admin');

        $this->actingAsAdmin($super)->post('/store-user-record', $this->newAdminPayload(['record_id' => $a->id, 'email' => $a->email]))
            ->assertSessionHasNoErrors();
        $this->actingAsAdmin($super)->post('/store-user-record', $this->newAdminPayload(['record_id' => $a->id, 'email' => $b->email]))
            ->assertSessionHasErrors('email');
    }

    public function test_only_super_admin_can_delete_users_and_never_themselves(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $admin = $this->makeAdmin('Admin');
        $victim = $this->makeAdmin('Admin');

        $this->actingAsAdmin($admin)->post("/delete-user/{$victim->id}");
        $this->assertDatabaseHas('admin_registers', ['id' => $victim->id]);

        $this->actingAsAdmin($super)->post("/delete-user/{$super->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('admin_registers', ['id' => $super->id]);

        $this->actingAsAdmin($super)->post("/delete-user/{$victim->id}")->assertSessionHas('success');
        $this->assertDatabaseMissing('admin_registers', ['id' => $victim->id]);
    }

    public function test_user_deletion_is_not_possible_with_a_plain_get_link(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $victim = $this->makeAdmin('Admin');

        $this->actingAsAdmin($super)->get("/delete-user/{$victim->id}")->assertStatus(405);

        $this->assertDatabaseHas('admin_registers', ['id' => $victim->id]);
    }

    public function test_deleting_or_editing_a_missing_user_is_a_404_not_a_crash(): void
    {
        $super = $this->makeAdmin('SuperAdmin');

        $this->actingAsAdmin($super)->post('/delete-user/99999')->assertNotFound();
        $this->actingAsAdmin($super)->get('/edit-user/99999')->assertNotFound();
    }

    public function test_edit_sale_permission_comes_from_the_session_not_the_url(): void
    {
        $admin = $this->makeAdmin('Admin');
        $super = $this->makeAdmin('SuperAdmin');
        $buyer = $this->makeBuyer();

        // A plain Admin claiming to be the SuperAdmin by putting that id in the URL.
        $this->actingAsAdmin($admin)->get("/edit/{$buyer}/{$super->id}")->assertSessionHas('error');

        $this->actingAsAdmin($super)->get("/edit/{$buyer}/{$super->id}")->assertOk();
    }
}
