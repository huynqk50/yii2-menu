<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 6/8/2017
 * Time: 10:24 AM
 */

namespace vanquyet\menu;

use Yii;

class Menu
{
    public $activeItemKey = null;
    public $data = [];
//    public $cacheKeyPrefix = '';
//    public $enableCache = false;
//    public $cacheDuration = 600;

    public function init($data, $config = [])
    {
        $this->_setConfig($config);
        $this->_setData($data);
        $this->_setActiveItemKey();
    }

    public function getActiveItem()
    {
        return isset($this->data[$this->activeItemKey]) ? $this->data[$this->activeItemKey] : null;
    }

    public function getRootItems()
    {
//        $cache_key = $this->cacheKeyPrefix . __METHOD__;
//        $topParents = Yii::$app->cache->get($cache_key);
//        if ($topParents === false || !$this->enableCache) {
            $topParents = [];
            foreach ($this->data as $key => $item) {
                if ($item->parentKey === null) {
                    $topParents[$key] = $item;
                }
            }
//            Yii::$app->cache->set($cache_key, $topParents, $this->cacheDuration);
//        }
        return $topParents;
    }

    private function _setConfig(array $config)
    {
        $attributes = ['cacheKeyPrefix', 'enableCache', 'cacheDuration'];
        foreach ($attributes as $attribute) {
            if (isset($config[$attribute])) {
                $this->$attribute = $config[$attribute];
            }
        }
    }

    private function _setData(array $data)
    {
//        $cache_key = $this->cacheKeyPrefix . __METHOD__;
//        $this->data = Yii::$app->cache->get($cache_key);
//        if ($this->data === false || !$this->enableCache) {
            $this->data = [];
            foreach ($data as $objectName => $objectData) {
                foreach ($objectData as $key => $item) {
                    $m = new MenuItem($this);
                    $m->label = $item['label'];
                    $m->url = $item['url'];
                    if (isset($item['options'])) {
                        $m->options = $item['options'];
                    }
                    $m->key = "{$objectName}_{$key}";
                    $m->parentKey =
                        isset($item['parentKey']) && $item['parentKey'] !== null
                            ? "{$objectName}_{$item['parentKey']}"
                            : null;
                    $this->data[$m->key] = $m;
                }
            }
//            Yii::$app->cache->set($cache_key, $this->data, $this->cacheDuration);
//        }
    }

    private function _setActiveItemKey()
    {
        $this->activeItemKey = '__';

        $getUrlSlugs = function ($url) {
            return explode('/', str_replace(['http://', 'https://'], '', trim(trim($url), '/')));
        };

        $url = Yii::$app->request->absoluteUrl;
        is_numeric($questionMarkPos = strpos($url, '?'))
            && $url = substr($url, 0, $questionMarkPos - strlen($url));
        $arr1 = $getUrlSlugs($url);

        $samePoint = 0; // min
        foreach ($this->data as $key => $item) {
            $arr2 = $getUrlSlugs($item->url);
            if (count($arr1) - count($arr2) >= 0) {
                $same = count(array_intersect($arr1, $arr2));
                $diff = count(array_diff($arr1, $arr2));
                if ($same - $diff > $samePoint) {
                    $samePoint = $same - $diff;
                    $this->activeItemKey = $key;
                }
            }
        }
    }
}
