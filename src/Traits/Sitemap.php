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
            $key = $this->getName($model);
            if(class_exists($model)){
                $updated_at = Cache::get($key, $this->getUpdateTime($model, $key));
                if($updated_at){
                    $models[] = (object)[
                        'name' => $this->getName($model),
                        'updated_at' => $updated_at,
                    ];
                }
            }
        }
        $models = (object)$models;
        Cache::put('models', $models, config('sitemap-xml.cacheLifetime', 0) );
        return $models;
    }


    public function getName($path){
        $items = 'model';
        foreach (explode("\\", $path) as $item){
            if($item) $items = $items . '-' . $item;
        }
        return strtolower($items);
    }

    public function getModel($name){
        $items = '';
        foreach (explode("-", $name) as $item){
            if($item != 'model') $items = $items . '\\' . ucfirst($item);
        }
        return $items;
    }

    /**
     * @param $class
     * @param $key
     * @return mixed|null
     */
    public function getUpdateTime($model, $key){
        try{
            $item = $model::query()->whereNotNull('published_at')->orderBy('updated_at', 'desc')->firstOrFail();
            Cache::put($key, $item->updated_at, config('sitemap-xml.cacheLifetime', 0));
            return $item->updated_at;
        } catch (\Exception $e) {
            return null;
        }
    }

}
