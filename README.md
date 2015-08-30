# kartinki

Creating image thumbnails with simple configs like "200x200", "1280x720:fit,quality=75".

[![Latest Stable Version](https://poser.pugx.org/kartinki/kartinki/v/stable)](https://packagist.org/packages/kartinki/kartinki)
[![Build Status](https://travis-ci.org/kartinki/kartinki.svg?branch=master)](https://travis-ci.org/kartinki/kartinki)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kartinki/kartinki/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kartinki/kartinki/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cdfcdd4e-7b80-440c-80f1-9f0c3f69b117/mini.png)](https://insight.sensiolabs.com/projects/cdfcdd4e-7b80-440c-80f1-9f0c3f69b117)
[![License](https://poser.pugx.org/kartinki/kartinki/license)](https://packagist.org/packages/kartinki/kartinki)

## Requirements

* PHP 5.4+, 7.0, HHVM
* GD extension enabled

## Installing

* Add `"kartinki/kartinki": "~1.0.0"` to `composer.json`.
* Run `composer install`

## Simple usage example

`filename.jpg` is 1600x1200.

```php
<?php

$config = [
    'square' => '200x200',
    'normal' => '400x400:fit',
    'big'    => '1280x720:fit,quality=100',
];

$result = (new kartinki\Kartinki\Thumbnailer)->createThumbnails(
    '/path/to/image/filename.jpg',
    $config,
    '/output/dir' // optional
);
```

Thumbnailer will place 3 files to /output/dir:

```
1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg // 200x200
1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg // 400x300
1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg    // 960x720
```

And $result will be instanse of kartinki\Kartinki\Result:

```php
<?php
$result->getThumbnails();
// [
//     'square' => '1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg',
//     'normal' => '1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg',
//     'big'    => '1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg'
// ]
 
$result->getUniqueId();
// '1ceebb2cf4b0425a0ea1e1cb49810a07'

$result->getExtension();
// 'jpg'

```

## Versioning

From version 1.0.0 kartinki uses [Semantic Versioning](http://semver.org/).
