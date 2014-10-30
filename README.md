# kartinki

Simple tool for creating previews of images.

[![Latest Stable Version](https://poser.pugx.org/happyproff/kartinki/v/stable.svg)](https://packagist.org/packages/happyproff/kartinki)
[![Build Status](https://travis-ci.org/happyproff/kartinki.svg?branch=master)](https://travis-ci.org/happyproff/kartinki)


## Installing

* Add `"happyproff/kartinki": "*"` to `composer.json`.
* Run `composer install`

## Usage example

### Simple

For example, `filename.jpg` is 1600x1200.

``` php
<?php
$kartinki = new happyproff\Kartinki\Kartinki;

$thumbnails = $kartinki->createImageVersions(
    '/path/to/image/filename.jpg',
    [
        'square' => '200x200',
        'normal' => '400x400:fit',
        'big'    => '1280x720:fit,quality=100',
    ],
    '/output/dir' // optional
);

var_dump($thumbnails);
```

Output:
```
[
    'square' => '1ceebb2cf4b0425a0ea1e1cb49810a07_square.jpg', // 200x200
    'normal' => '1ceebb2cf4b0425a0ea1e1cb49810a07_normal.jpg', // 400x300
    'big'    => '1ceebb2cf4b0425a0ea1e1cb49810a07_big.jpg',    // 960x720
]
```
