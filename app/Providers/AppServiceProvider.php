<?php

namespace App\Providers;

use App\Services\SidebarMenuService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        View::composer('*', function ($view) {
            try {
                $sidebar = app(SidebarMenuService::class)->dataForLoggedUser(
                    session('LoggedUser'),
                    request()->path()
                );
            } catch (\Exception $e) {
                Log::error('Sidebar menu load failed: ' . $e->getMessage());

                $sidebar = [
                    'data' => null,
                    'menu' => collect(),
                    'submenu' => collect(),
                    'submenu2' => collect(),
                    'headeer' => collect(),
                ];
            }

            $view->with($sidebar);
        });
    }
}
