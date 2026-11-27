<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use ReflectionMethod;
use Tests\Support\BuildsSozoData;
use Tests\TestCase;

/**
 * Structural guards for a 4,000-line controller: every route must point at real code,
 * no routed action may halt on a debug call, and every module must render with real data.
 */
class RouteIntegrityTest extends TestCase
{
    use RefreshDatabase, BuildsSozoData;

    /** Actions that delete/alter data when called, so a blind smoke GET must not call them. */
    private const DESTRUCTIVE = [
        'delete-user/{id}', 'delete/{id}/{plot_number}/{estate}', 'admin-logout',
        'back-on-market', 'back-on-market-all', 'get-all-estate-plots',
    ];

    private function appRoutes(): array
    {
        return array_filter(iterator_to_array(Route::getRoutes()), function ($r) {
            return str_starts_with($r->getActionName(), 'App\\');
        });
    }

    public function test_every_route_points_at_an_existing_controller_method(): void
    {
        $missing = [];
        foreach ($this->appRoutes() as $route) {
            [$class, $method] = explode('@', $route->getActionName()) + [1 => '__invoke'];
            if (!method_exists($class, $method)) {
                $missing[] = $route->uri() . " -> {$class}::{$method}";
            }
        }

        $this->assertSame([], $missing, "Routes pointing at missing methods:\n" . implode("\n", $missing));
    }

    public function test_no_routed_action_contains_a_debug_halt(): void
    {
        $offenders = [];
        foreach ($this->appRoutes() as $route) {
            [$class, $method] = explode('@', $route->getActionName()) + [1 => '__invoke'];
            if (!method_exists($class, $method)) {
                continue;
            }
            $ref = new ReflectionMethod($class, $method);
            $src = implode('', array_slice(file($ref->getFileName()), $ref->getStartLine() - 1, $ref->getEndLine() - $ref->getStartLine() + 1));

            if (preg_match('/\b(dd|dump|var_dump|print_r|die)\s*\(/', $src)) {
                $offenders[] = $route->uri() . " ({$method})";
            }
        }

        $this->assertSame([], $offenders, "Routed actions with leftover debug calls:\n" . implode("\n", $offenders));
    }

    public function test_no_url_and_verb_is_registered_twice(): void
    {
        preg_match_all("/Route::(get|post|put|patch|delete|view|redirect)\('([^']+)'/", file_get_contents(base_path('routes/web.php')), $m, PREG_SET_ORDER);

        $seen = $dups = [];
        foreach ($m as [, $verb, $uri]) {
            $key = strtoupper($verb) . ' ' . ltrim($uri, '/');
            isset($seen[$key]) ? $dups[] = $key : $seen[$key] = true;
        }

        $this->assertSame([], $dups, "Duplicate route registrations (the later silently overrides the earlier):\n" . implode("\n", $dups));
    }

    public function test_every_named_route_used_by_a_view_exists(): void
    {
        $names = [];
        foreach (glob(resource_path('views') . '/{*,*/*,*/*/*}.blade.php', GLOB_BRACE) as $file) {
            preg_match_all("/route\(\s*['\"]([\w\.\-\/\{\}]+)['\"]/", file_get_contents($file), $m);
            foreach ($m[1] as $n) {
                $names[$n] = $file;
            }
        }

        $missing = [];
        foreach ($names as $name => $file) {
            if (!Route::has($name)) {
                $missing[] = "$name (in " . str_replace(base_path() . '/', '', $file) . ')';
            }
        }

        $this->assertSame([], $missing, "Views link to routes that do not exist:\n" . implode("\n", $missing));
    }

    public function test_every_module_page_renders_with_realistic_data(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $this->makeEstate();
        $this->makePlot(['plot_number' => '1', 'status' => 'Partially payed', 'buyer_id' => '1']);
        $this->makePlot(['plot_number' => '2']);
        $buyer = $this->makeBuyer();
        \DB::table('reciepts')->insert(['user_id' => (string) $buyer, 'Amount' => '1,000,000', 'Balance' => '4,000,000', 'reciept' => 'r.pdf', 'Phonenumber' => '0700', 'amount_in_words' => 'one million', 'Date_of_payment' => '2025-01-01', 'created_at' => now(), 'updated_at' => now()]);

        $failures = [];
        $rendered = 0;

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods()) || !str_starts_with($route->getActionName(), 'App\\')) {
                continue;
            }
            if (in_array($route->uri(), self::DESTRUCTIVE, true)) {
                continue;
            }

            $url = str_replace(['{plot_number}', '{estate}'], ['1', 'Kayabwe'], $route->uri());
            $url = '/' . preg_replace('/\{[^}]+\}/', '1', $url);

            $status = $this->actingAsAdmin($super)->get($url)->getStatusCode();
            $rendered++;

            // 404 is a legitimate "that record has no file/page", 5xx is a bug.
            if ($status >= 500) {
                $failures[] = "$status {$route->uri()}";
            }
        }

        $this->assertGreaterThan(60, $rendered);
        $this->assertSame([], $failures, "Pages that crash with realistic data:\n" . implode("\n", $failures));
    }

    public function test_pages_do_not_crash_for_ids_that_do_not_exist(): void
    {
        $super = $this->makeAdmin('SuperAdmin');
        $crashes = [];

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods()) || !str_contains($route->uri(), '{')
                || !str_starts_with($route->getActionName(), 'App\\') || in_array($route->uri(), self::DESTRUCTIVE, true)) {
                continue;
            }
            $url = '/' . preg_replace('/\{[^}]+\}/', '999999', $route->uri());
            $status = $this->actingAsAdmin($super)->get($url)->getStatusCode();
            if ($status >= 500) {
                $crashes[] = "$status {$route->uri()}";
            }
        }

        $this->assertSame([], $crashes, "A missing record should be a 404, but these return 5xx:\n" . implode("\n", $crashes));
    }
}
