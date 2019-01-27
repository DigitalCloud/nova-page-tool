<?php

namespace DigitalCloud\PageTool;

use DigitalCloud\PageTool\Resources\Page;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use DigitalCloud\PageTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'page-tool');

		$this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->app->booted(function () {
            Nova::resources([
                Page::class
            ]);
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/digital-cloud/page-tool')
                ->group(__DIR__.'/../routes/api.php');

        Route::middleware(['web'])
            ->group(__DIR__.'/../routes/web.php');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
