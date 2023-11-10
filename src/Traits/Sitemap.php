<?php

namespace Cher4geo35\Sitemap\Traits;

use App\Menu;
use Illuminate\Support\Facades\Cache;

trait Sitemap
{

    /**
     * @return null
     */
    public function getTimeMenuUpdate(){
        try{
            $menu = Menu::query()->where('key', 'main')->firstOrFail();
            $menu = $menu->items()->orderBy('updated_at', 'desc')->firstOrFail();
            Cache::put('sitemap-menu-update', $menu, config('sitemap-xml.cacheLifetime', 0));
            return $menu;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|null
     */
    private function getMenuItems() {
        try{
            $menu = Menu::query()->where('key', 'main')->firstOrFail();
            $menuItems = $menu->items;
            Cache::put('sitemap-menuItems', $menu, config('sitemap-xml.cacheLifetime', 0));
            return $menuItems;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return object
     */
    public function getUpdateModels(){
        $routes = [];
        foreach (config('sitemap-xml.models', []) as $model => $route){
            $key = $this->getName($route);
            if(class_exists($model)){
                $updated_at = Cache::get($key, $this->getUpdateTime($route, $key));
                if($updated_at){
                    $routes[] = (object)[
                        'name' => $this->getName($route),
                        'updated_at' => $updated_at,
                    ];
                }
            }
        }
        $routes = (object)$routes;
        Cache::put('routes', $routes, config('sitemap-xml.cacheLifetime', 0) );
        return $routes;
    }


    public function getName($route){
        $items = '';
        foreach (explode(".", $route) as $item){
            if(!$items){
                $items = $items . $item;
            } else {
                $items = $items . '-' . $item;
            }
        }
        return strtolower($items . '.xml');
    }

    /**
     * @param $class
     * @param $key
     * @return mixed|null
     */
    public function getUpdateTime($route, $key){
        $model = array_search($route, config('sitemap-xml.models'));
        try{
            $item = $model::query()->whereNotNull('published_at')->orderBy('updated_at', 'desc')->firstOrFail();
            Cache::put($key, $item->updated_at, config('sitemap-xml.cacheLifetime', 0));
            return $item->updated_at;
        } catch (\Exception $e) {
            return null;
        }
    }

}
