<?php
/**
 * Created by PhpStorm.
 * User: Quyet
 * Date: 6/8/2017
 * Time: 10:24 AM
 */

namespace vanquyet\menu;


use yii\helpers\Html;
use Yii;

class MenuItem
{
    public $key = null;
    public $parentKey = null;
    public $url = '#';
    public $label = '';
    public $options = [];
    private $menu = null;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function a($options = [], $content = null)
    {
        return Html::a($content !== null ? $content : $this->label, array_merge($options, $this->options));
    }

    public function isActive()
    {
        return in_array($this->menu->activeItemKey, array_merge([$this->key], array_keys($this->getAllChildren())));
    }

    public function getChildren()
    {
//        $cacheKey = $this->cacheKeyPrefix . __METHOD__ . "@$this->key";
//        $result = Yii::$app->cache->get($cacheKey);
//        if ($result === false || !$this->enableCache) {
            $result = [];
            foreach ($this->menu->data as $key => $item) {
                if ($item->parentKey === $this->key) {
                    $result[$key] = $item;
                }
            }
//            Yii::$app->cache->set($cacheKey, $result, $this->cacheDuration);
//        }
        return $result;
    }

    public function getParent()
    {
//        $cacheKey = $this->cacheKeyPrefix . __METHOD__ . "@$this->key";
//        $result = Yii::$app->cache->get($cacheKey);
//        if ($result === false || !$this->enableCache) {
            $result = null;
            foreach ($this->menu->data as $item) {
                if ($item->key === $this->parentKey) {
                    $result = $item;
                    return $item;
                    break;
                }
            }
//            Yii::$app->cache->set($cacheKey, $result, $this->cacheDuration);
//        }
        return $result;
    }

    public function getAllChildren()
    {
//        $cacheKey = $this->cacheKeyPrefix . __METHOD__ . "@$this->key";
//        $result = Yii::$app->cache->get($cacheKey);
//        if ($result === false || !$this->enableCache) {
            $result = $this->getChildren();
            foreach ($result as $item) {
                $result = array_merge($result, $item->getAllChildren());
            }
//            Yii::$app->cache->set($cacheKey, $result, $this->cacheDuration);
//        }
        return $result;
    }

}