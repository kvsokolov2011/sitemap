# sitemap

**Генерация sitemap.xml**

## Установка

    php artisan vendor:publish --provider="Cher4geo35\Sitemap\SitemapServiceProvider"
    php artisan make:sitemap {--all : Run all}
                            {--controllers : Export controllers}

### Versions

## 2.0.8
    Добавлена возможность добавлять URL адреса в конфиг

## 2.0.6
    Добавлена возможность фильтрации по полям моделей

## 2.0.4
    Добавлена возможность доблять роуты в конфиг
