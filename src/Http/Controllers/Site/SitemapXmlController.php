<?php

namespace Cher4geo35\Sitemap\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Cher4geo35\Sitemap\Traits\Sitemap;
use Illuminate\Support\Facades\Cache;

class SitemapXmlController extends Controller
{
    use Sitemap;

    public function index() {
        return response()->view('sitemap::site.sitemap.index', [
            'menu' => Cache::get('sitemap-menu-update', function(){return $this->getTimeMenuUpdate();}),
            'models' => Cache::get('models', function(){return $this->getUpdateModels();}),
        ])->header('Content-Type', 'text/xml');
    }

    public function menu() {
        return response()->view('sitemap::site.sitemap.menu', [
            'menuItems' => Cache::get('sitemap-menuItems', function(){return $this->getMenuItems();}),
        ])->header('Content-Type', 'text/xml');
    }

    public function model($model){
        $key = 'sitemap'.$model;
        $name = explode('-', $model);
        $name = end($name);
        $model = $this->getModel($model);
        if(!Cache::get('key')) $models = $this->getModels($model, $key);
        return response()->view('sitemap::site.sitemap.links', [
            'models' => $models,
            'name' => $name,
            'route' => config('sitemap-xml.models')[$model],
        ])->header('Content-Type', 'text/xml');
    }

    public function getModels($model, $key){
        $items = $model::query()->whereNotNull('published_at')->get();
        Cache::put($key, $items, config('sitemap-xml.cacheLifetime', 0));
        return $items;
    }

}
