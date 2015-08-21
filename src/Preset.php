<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Interfaces\PresetInterface;
use happyproff\Kartinki\Exceptions\InvalidArgumentException;

class Preset implements PresetInterface
{
    const DEFAULT_QUALITY = 85;

    protected $width;
    protected $height;
    protected $fit;
    protected $quality;

    public function __construct($width = 0, $height = 0)
    {
        $this->width = $width;
        $this->height = $height;
        $this->quality = self::DEFAULT_QUALITY;
    }

    /**
     * @param array
     *
     * @return static
     */
    public static function createFromArray(array $params)
    {
        $preset = new self;
        if (isset($params['width'])) {
            $preset->setWidth($params['width']);
        }
        if (isset($params['height'])) {
            $preset->setHeight($params['height']);
        }
        if (isset($params['fit'])) {
            $preset->setFit($params['fit']);
        }
        if (isset($params['quality'])) {
            $preset->setQuality($params['quality']);
        }

        return $preset;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        if (!is_int($width)) {
            throw new InvalidArgumentException('$width must be an int.');
        }

        $this->width = $width;

        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        if (!is_int($height)) {
            throw new InvalidArgumentException('$height must be an int.');
        }

        $this->height = $height;

        return $this;
    }

    public function isFit()
    {
        return $this->fit;
    }

    public function setFit($fit)
    {
        if (!is_bool($fit)) {
            throw new InvalidArgumentException('$fit must be a bool.');
        }

        $this->fit = $fit;

        return $this;
    }

    public function getQuality()
    {
        return $this->quality;
    }

    public function setQuality($quality)
    {
        if (!is_int($quality)) {
            throw new InvalidArgumentException('$quality must be an int.');
        }
        if ($quality < 0 || $quality > 100) {
            throw new InvalidArgumentException('$quality must be 0..100.');
        }

        if ($quality === 0) {
            $quality = self::DEFAULT_QUALITY;
        }

        $this->quality = $quality;

        return $this;
    }
}
