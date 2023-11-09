<?php

namespace Cher4geo35\Sitemap;

use Cher4geo35\Sitemap\Console\Commands\SitemapMakeCommand;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Подгрузка шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'sitemap');

        // Подгрузка роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Конфигурация.
        $this->publishes([
            __DIR__ . '/config/sitemap-xml.php' => config_path('sitemap-xml.php'),
        ], 'config');

//      // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                SitemapMakeCommand::class,
            ]);
        }

    }

    public function register()
    {

    }
}
