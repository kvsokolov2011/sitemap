<?php

use Cher4geo35\Sitemap\Http\Controllers\Site\SitemapXmlController;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\Sitemap\Site',
    'as' => 'sitemap.'
], function () {
    Route::get('/sitemap.xml', [SitemapXmlController::class, 'index']);
    Route::get('/sitemap.xml/menu', [SitemapXmlController::class, 'menu'])->name('menu');
    Route::get('/sitemap.xml/{model}', [SitemapXmlController::class, 'model'])->name('model');
});

