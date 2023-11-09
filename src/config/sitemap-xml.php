<?php

return [
    'cacheLifetime' => 0, //Время кеширования sitemap
    //Название моделей и их роуты
    'models' => [
                    'News' => 'site.news.show',
                    'Product' => 'catalog.products.show',
                    'Category' => 'catalog.categories.show',
                    'Page' => 'site.pages.show',
                    'Folder' => 'site.folders.show',
        ],
];
