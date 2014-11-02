<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Exceptions\InvalidArgumentException;
use happyproff\Kartinki\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    const DEFAULT_QUALITY = 85;
    protected $width;
    protected $height;
    protected $fit;
    protected $quality;

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        if (!is_int($width)) {
            throw new InvalidArgumentException('$width must be int.');
        }

        $this->width = $width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        if (!is_int($height)) {
            throw new InvalidArgumentException('$height must be int.');
        }

        $this->height = $height;
    }

    public function isFit()
    {
        return $this->fit;
    }

    public function setFit($fit)
    {
        if (!is_bool($fit)) {
            throw new InvalidArgumentException('$fit must be bool.');
        }

        $this->fit = $fit;
    }

    public function getQuality()
    {
        return $this->quality;
    }

    public function setQuality($quality)
    {
        if (!is_int($quality)) {
            throw new InvalidArgumentException('$quality must be int.');
        }
        if ($quality < 0 || $quality > 100) {
            throw new InvalidArgumentException('$quality must be 0..100.');
        }

        if ($quality === 0) {
            $quality = self::DEFAULT_QUALITY;
        }

        $this->quality = $quality;
    }
}
