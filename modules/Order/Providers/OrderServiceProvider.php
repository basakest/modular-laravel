<?php

namespace Modules\Order\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'order');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->app->register(EventServiceProvider::class);

        $this->loadViewsFrom(__DIR__ . '/../Ui/Views', 'order');
        Blade::componentNamespace('Modules\\Order\\ViewComponents', 'order');
        Blade::anonymousComponentNamespace(__DIR__ . '/../Ui/Views/components', 'order');
    }
}
