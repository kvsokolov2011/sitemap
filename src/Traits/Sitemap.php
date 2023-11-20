<?php

namespace Cher4geo35\Sitemap\Traits;

use App\Menu;
use Illuminate\Support\Facades\Cache;
use PortedCheese\BaseSettings\Models\SiteConfig;

trait Sitemap
{

    /**
     * @param $key_menu
     * @return null
     */
    private function getTimeMenuUpdate($key_menu){
        try{
            $menu = Menu::query()->where('key', 'main')->firstOrFail();
            $menu = $menu->items()->orderBy('updated_at', 'desc')->firstOrFail();
            Cache::put($key_menu, $menu, config('sitemap-xml.cacheLifetime', 0));
            return $menu;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed|null
     */
    private function getMenuItems($key_menu_items) {
        try{
            $menu = Menu::query()->where('key', 'main')->firstOrFail();
            $menuItems = $menu->items;
            Cache::put($key_menu_items, $menuItems, config('sitemap-xml.cacheLifetime', 0));
            return $menuItems;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $key_manual_items
     * @return null
     */
    private function getManualItems($key_manual_items) {
        try{
            $menu = SiteConfig::query()->orderBy('updated_at', 'desc')->firstOrFail();
            $updated_at = $menu->updated_at;
            Cache::put($key_manual_items, $updated_at, config('sitemap-xml.cacheLifetime', 0));
            return $updated_at;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return object
     */
    private function getUpdateModels($key_route){
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
        Cache::put($key_route, $routes, config('sitemap-xml.cacheLifetime', 0) );
        return $routes;
    }

    /**
     * @param $route
     * @return string
     */
    private function getName($route){
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
    private function getUpdateTime($route, $key){
        $model = array_search($route, config('sitemap-xml.models'));
        try{
            if(isset(config('sitemap-xml.filter')[$model])){
                $item = $model::query()->whereNotNull(config('sitemap-xml.filter')[$model])->orderBy('updated_at', 'desc')->firstOrFail();
            } else {
                $item = $model::query()->orderBy('updated_at', 'desc')->firstOrFail();
            }
            Cache::put($key, $item->updated_at, config('sitemap-xml.cacheLifetime', 0));
            return $item->updated_at;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $pattern
     * @return int|string|void
     */
    private function modelSearch($pattern){
        foreach(config('sitemap-xml.models', []) as $model_conf => $model_item){
            $model_item = preg_replace("(-)", ".", $model_item);
            if( $model_item == $pattern) return $model_conf;
        }
    }

}
