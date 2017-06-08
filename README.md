Yii2 Menu
=========
Arranges your links in groups that are easy to use to render different HTML codes

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vanquyet/yii2-menu "*"
```

or add

```
"vanquyet/yii2-menu": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
use vanquyet\menu\Menu;
$menu = new Menu();
$menu->init(
    [
        'info' => [
            'homePage' => [
                'label' => 'Trang chủ',
                'url' => Url::home(true),
                'parentKey' => null
            ],
            'aboutPage' => [
                'label' => 'Giới thiệu',
                'url' => Url::to(['site/about'], true),
                'parentKey' => null
            ],
            'teachers' => [
                'label' => 'Giáo viên',
                'url' => '#',
                'parentKey' => 'aboutPage'
            ]
        ]
    ]
);

var_dump($menu->getRootItems());
```