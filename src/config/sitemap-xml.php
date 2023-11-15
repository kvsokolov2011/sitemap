<?php

return [
    'cacheLifetime' => 86400, //Время кеширования sitemap
    //Название моделей и их роуты
    'models' => [
                    '\App\News' => 'site.news.show',
                    '\App\Product' => 'catalog.products.show',
                    '\App\Category' => 'catalog.categories.show',
                    '\App\Page' => 'site.pages.show',
                    '\App\Folder' => 'site.folders.show',
                    '\App\DocumentCategory' => 'site.document-categories.show',
        ],
    'filter' => [
        '\App\News' => 'published_at',
        '\App\Product' => 'published_at',
        '\App\Category' => 'published_at',
        '\App\Page' => 'published_at',
        '\App\Folder' => 'published_at',
    ],
    'manual' => ['site.news.index'],// это только как пример роута
];
