# kartinki

Simple tool for creating previews of images.

[![Latest Stable Version](https://poser.pugx.org/happyproff/kartinki/v/stable.svg)](https://packagist.org/packages/happyproff/kartinki)
[![Build Status](https://travis-ci.org/happyproff/kartinki.svg?branch=master)](https://travis-ci.org/happyproff/kartinki)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/00cfb6a2-1b82-4559-91e4-5e6339e5c327/mini.png)](https://insight.sensiolabs.com/projects/00cfb6a2-1b82-4559-91e4-5e6339e5c327)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/happyproff/kartinki/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/happyproff/kartinki/?branch=master)

## Installing

* Add `"happyproff/kartinki": "*"` to `composer.json`.
* Run `composer install`

## Simple usage example

For example, `filename.jpg` is 1600x1200.

``` php
<?php
use happyproff\Kartinki\Kartinki;

$thumbnails = (new Kartinki)->createImageVersions(
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

And $thumbnails will contain:
``` php
<?php
[
    'square' => '1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg',
    'normal' => '1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg',
    'big'    => '1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg'
]
```
