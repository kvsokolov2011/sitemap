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
            'routes' => Cache::get('routes', function(){return $this->getUpdateModels();}),
        ])->header('Content-Type', 'text/xml');
    }

    public function menu() {
        return response()->view('sitemap::site.sitemap.menu', [
            'menuItems' => Cache::get('sitemap-menuItems', function(){return $this->getMenuItems();}),
        ])->header('Content-Type', 'text/xml');
    }

    public function route($route){
        $key = 'sitemap-'.$route;
        $route = preg_replace("(.xml)", "", $route);
        $route = preg_replace("(-)", ".", $route);
        $model = array_search($route, config('sitemap-xml.models'));
        if(!class_exists($model)) return redirect(route('home'));
        $name = explode('\\', $model);
        $name = strtolower(end($name));
        if(!Cache::get($key)) $this->getModels($model, $key);
        return response()->view('sitemap::site.sitemap.links', [
            'models' => Cache::get($key),
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
