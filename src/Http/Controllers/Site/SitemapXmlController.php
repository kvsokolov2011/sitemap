<?php

namespace Cher4geo35\Sitemap\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Cher4geo35\Sitemap\Traits\Sitemap;
use Illuminate\Support\Facades\Cache;

class SitemapXmlController extends Controller
{
    use Sitemap;

    /**
     * @return mixed
     */
    public function index() {
        $key_menu = 'sitemap-menu';
        $key_manual = 'sitemap-manual';
        $key_route = 'sitemap-route';
        return response()->view('sitemap::site.sitemap.index', [
            'menu' => Cache::get($key_menu, function() use ($key_menu) {return $this->getTimeMenuUpdate($key_menu);}),
            'manual' => Cache::get($key_manual, function() use ($key_manual) {return $this->getManualItems($key_manual);}),
            'routes' => Cache::get($key_route , function() use ($key_route) {return $this->getUpdateModels($key_route);}),
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * @return mixed
     */
    public function menu() {
        $key_menu_items = 'sitemap-menu-items';
        return response()->view('sitemap::site.sitemap.menu', [
            'menuItems' => Cache::get($key_menu_items, function() use ($key_menu_items) {return $this->getMenuItems($key_menu_items);}),
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * @return mixed
     */
    public function manual() {
        $key_manual_items = 'sitemap-manual-items';
        return response()->view('sitemap::site.sitemap.manual', [
            'updated_at' => Cache::get($key_manual_items, function() use ($key_manual_items) {return $this->getManualItems($key_manual_items);}),
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * @param $route
     * @return mixed
     */
    public function route($route){
        $key = 'sitemap-'.$route;
        $route = preg_replace("(.xml)", "", $route);
        $route = preg_replace("(-)", ".", $route);
        $model = $this->modelSearch($route);
        if(!class_exists($model)) return redirect(route('home'));
        $name = explode('\\', $model);
        $name = preg_split("/(?<=[a-z])(?![a-z])/", end($name), -1, PREG_SPLIT_NO_EMPTY);
        $name = strtolower(end($name));
        if(!Cache::get($key)) $this->getModels($model, $key);
        return response()->view('sitemap::site.sitemap.links', [
            'models' => Cache::get($key),
            'name' => $name,
            'route' => config('sitemap-xml.models')[$model],
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * @param $model
     * @param $key
     * @return mixed
     */
    private function getModels($model, $key){
        if(isset(config('sitemap-xml.filter')[$model])){
            $items = $model::query()->whereNotNull('published_at')->get();
        } else {
            $items = $model::all();
        }
        Cache::put($key, $items, config('sitemap-xml.cacheLifetime', 0));
        return $items;
    }


}
