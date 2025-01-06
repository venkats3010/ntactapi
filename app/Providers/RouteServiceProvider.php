<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespace for the controller.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Example: You can define route model bindings, custom pattern filters, etc.
        // Here you can register custom route patterns
        Route::pattern('id', '[0-9]+'); // Define pattern for route parameters
    }

    /**
     * Define the routes for your application.
     *
     * @return void
     */
    public function map()
    {
        // This method defines how routes are loaded into your application.

        // Load web routes for non-API requests
        $this->mapWebRoutes();

        // Load API routes for API requests
        $this->mapApiRoutes();

        // Optionally, load other custom routes
        $this->mapCustomRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define custom routes (optional).
     *
     * @return void
     */
    protected function mapCustomRoutes()
    {

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
}
