<?php

namespace Modules\Reward\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Reward';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        // $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/Routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/v1/general/user/')
            ->name('api.v1.general.user.')
            ->group(module_path($this->name, '/routes/v1/general.php'));
        Route::middleware(['api', 'jwt.scope:customer'])
            ->prefix('api/v1/customer/user/')
            ->name('api.v1.customer.user.')
            ->group(module_path($this->name, '/routes/v1/customer.php'));
        Route::middleware(['api', 'jwt.scope:admin'])
            ->prefix('api/v1/admin/user/')
            ->name('api.v1.admin.user.')
            ->group(module_path($this->name, '/routes/v1/admin.php'));
    }
}
