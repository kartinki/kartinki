# kartinki

Simple tool for generating image thumbnails.

[![Latest Stable Version](https://poser.pugx.org/kartinki/kartinki/v/stable.svg)](https://packagist.org/packages/kartinki/kartinki)
[![Build Status](https://travis-ci.org/kartinki/kartinki.svg?branch=master)](https://travis-ci.org/kartinki/kartinki)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kartinki/kartinki/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kartinki/kartinki/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/00cfb6a2-1b82-4559-91e4-5e6339e5c327/mini.png)](https://insight.sensiolabs.com/projects/00cfb6a2-1b82-4559-91e4-5e6339e5c327)

## Installing

* Add `"kartinki/kartinki": "*"` to `composer.json`.
* Run `composer install`

## Simple usage example

For example, `filename.jpg` is 1600x1200.

```php
<?php
use kartinki\Kartinki\Kartinki;

$result = (new Kartinki)->createThumbnails(
    '/path/to/image/filename.jpg',
    [
        'square' => '200x200',
        'normal' => '400x400:fit',
        'big'    => '1280x720:fit,quality=100',
    ],
    '/output/dir' // optional
);
```

Kartinki will place 3 files to /output/dir:

```
1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg // 200x200
1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg // 400x300
1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg    // 960x720
```

And $result will be instanse of kartinki\Kartinki\Result:

```php
<?php
$result->getVersions();
// [
//     'square' => '1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg',
//     'normal' => '1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg',
//     'big'    => '1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg'
// ]
 
$result->getId();
// '1ceebb2cf4b0425a0ea1e1cb49810a07'

$result->getExt();
// 'jpg'

```

## Versioning

From version 1.0.0 kartinki uses [Semantic Versioning](http://semver.org/).
