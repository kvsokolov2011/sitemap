<?php

return [
    'cacheLifetime' => 0, //Время кеширования sitemap
    //Название моделей и их роуты
    'models' => [
                    '\App\News' => 'site.news.show',
                    '\App\Product' => 'catalog.products.show',
                    '\App\Category' => 'catalog.categories.show',
                    '\App\Page' => 'site.pages.show',
                    '\App\Folder' => 'site.folders.show',
        ],
];
