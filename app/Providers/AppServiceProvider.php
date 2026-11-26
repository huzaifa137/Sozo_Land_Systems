<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Records shown per page on every list in the system.
     */
    public const PER_PAGE = 10;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // One pagination design for the whole system. This is the same
        // bootstrap-5 markup the admin dashboard uses, styled by
        // public/assets/sozo/sozo.css, so ->links() looks identical everywhere.
        Paginator::useBootstrapFive();

        // Lets controllers paginate an already-built collection:
        //   $records->paginate(10, 'records_page')
        // $pageName lets one screen hold several independent paginated tables.
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate', function (int $perPage = AppServiceProvider::PER_PAGE, string $pageName = 'page') {
                /** @var Collection $this */
                $page = Paginator::resolveCurrentPage($pageName);

                return (new LengthAwarePaginator(
                    $this->forPage($page, $perPage)->values(),
                    $this->count(),
                    $perPage,
                    $page,
                    [
                        'path' => Paginator::resolveCurrentPath(),
                        'pageName' => $pageName,
                    ]
                ))->withQueryString();
            });
        }
    }
}
