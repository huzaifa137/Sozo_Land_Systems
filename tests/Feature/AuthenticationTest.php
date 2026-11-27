<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Support\BuildsSozoData;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, BuildsSozoData;

    public function test_public_pages_are_reachable_by_guests(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/privacy-policy')->assertOk();
    }

    public function test_every_admin_get_route_without_parameters_redirects_guests_to_login(): void
    {
        $checked = 0;

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods()) || str_contains($route->uri(), '{')) {
                continue;
            }
            if (!in_array('AdminAuth', $route->gatherMiddleware(), true)) {
                continue;
            }
            if (in_array($route->uri(), ['/', 'login', 'privacy-policy'], true)) {
                continue; // intentionally public
            }

            $this->get('/' . $route->uri())
                ->assertRedirect('login', );
            $checked++;
        }

        $this->assertGreaterThan(30, $checked, 'Expected to exercise many protected routes');
    }

    public function test_every_admin_post_route_rejects_guests(): void
    {
        foreach (Route::getRoutes() as $route) {
            if (!in_array('POST', $route->methods()) || !in_array('AdminAuth', $route->gatherMiddleware(), true)) {
                continue;
            }
            $uri = preg_replace('/\{[^}]+\}/', '1', $route->uri());
            $this->post('/' . $uri)->assertRedirect('login');
        }
    }

    public function test_valid_credentials_log_the_admin_in(): void
    {
        $admin = $this->makeAdmin('Admin', ['email' => 'boss@sozo.test']);

        $this->post('/admin-send', ['email' => 'boss@sozo.test', 'password' => 'password123'])
            ->assertRedirect('admin-dashboard');

        $this->assertSame($admin->id, session('LoggedAdmin'));
    }

    public function test_wrong_password_is_rejected_without_logging_in(): void
    {
        $this->makeAdmin('Admin', ['email' => 'boss@sozo.test']);

        $this->from('/login')->post('/admin-send', ['email' => 'boss@sozo.test', 'password' => 'nope'])
            ->assertRedirect('/login')
            ->assertSessionHas('fail');

        $this->assertNull(session('LoggedAdmin'));
    }

    public function test_unknown_email_is_rejected_with_the_same_generic_failure(): void
    {
        $this->from('/login')->post('/admin-send', ['email' => 'ghost@sozo.test', 'password' => 'x'])
            ->assertSessionHas('fail');
        $this->assertNull(session('LoggedAdmin'));
    }

    public function test_email_and_password_are_required(): void
    {
        $this->post('/admin-send', [])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_logged_in_admin_is_sent_away_from_the_login_page(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->get('/login')->assertRedirect(route('admin-dashboard'));
    }

    public function test_logout_clears_the_session(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAsAdmin($admin)->get('/admin-logout')->assertRedirect('/');

        $this->assertNull(session('LoggedAdmin'));
    }

    public function test_session_id_is_regenerated_on_login_to_prevent_fixation(): void
    {
        $this->makeAdmin('Admin', ['email' => 'boss@sozo.test']);
        $this->startSession();
        $before = session()->getId();

        // The visitor already holds a session cookie (what a fixation attacker plants).
        // Without regenerate() the same id would survive the login.
        $this->withCookie(config('session.cookie'), $before)
            ->post('/admin-send', ['email' => 'boss@sozo.test', 'password' => 'password123'])
            ->assertRedirect('admin-dashboard');

        $this->assertNotSame($before, session()->getId());
    }

    public function test_login_is_rate_limited_against_password_guessing(): void
    {
        $this->makeAdmin('Admin', ['email' => 'boss@sozo.test']);

        for ($i = 0; $i < 10; $i++) {
            $this->post('/admin-send', ['email' => 'boss@sozo.test', 'password' => "guess$i"]);
        }

        $this->post('/admin-send', ['email' => 'boss@sozo.test', 'password' => 'password123'])
            ->assertStatus(429);
    }
}
