<?php

use Cher4geo35\Sitemap\Http\Controllers\Site\SitemapXmlController;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\Sitemap\Site',
    'as' => 'sitemap.'
], function () {
    Route::get('/sitemap.xml', [SitemapXmlController::class, 'index']);
    Route::get('/sitemap/menu.xml', [SitemapXmlController::class, 'menu'])->name('menu');
    Route::get('/sitemap/manual.xml', [SitemapXmlController::class, 'manual'])->name('manual');
    Route::get('/sitemap/{route}', [SitemapXmlController::class, 'route'])->name('route');
});

