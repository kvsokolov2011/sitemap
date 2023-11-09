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
        $models = [];
        foreach (config('sitemap-xml.models', []) as $model => $route){
            $key = 'sitemap-'.strtolower($model);
            if(class_exists('\App\\'.$model)){
                $update_at = Cache::get($key, $this->getUpdateTime('\App\\'.$model, $key));
                if($update_at){
                    $models[] = (object)[
                        'name' => strtolower($model),
                        'updated_at' => $update_at,
                    ];
                }
            }
        }
        $models = (object)$models;
        Cache::put('models', $models, config('sitemap-xml.cacheLifetime', 0) );
        return $models;
    }

    /**
     * @param $class
     * @param $key
     * @return mixed|null
     */
    public function getUpdateTime($class, $key){
        try{
            $item = $class::query()->whereNotNull('published_at')->orderBy('updated_at', 'desc')->firstOrFail();
            Cache::put($key, $item, config('sitemap-xml.cacheLifetime', 0));
            return $item->updated_at;
        } catch (\Exception $e) {
            return null;
        }
    }

}
