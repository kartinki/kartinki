<?php

namespace happyproff\Kartinki\Interfaces;

interface ConfigInterface
{
    /**
     * @return int
     */
    public function getWidth();

    /**
     * @param int $width
     */
    public function setWidth($width);

    /**
     * @return int
     */
    public function getHeight();

    /**
     * @param int $height
     */
    public function setHeight($height);

    /**
     * @return boolean
     */
    public function isFit();

    /**
     * @param boolean $fit
     */
    public function setFit($fit);

    /**
     * @return int
     */
    public function getQuality();

    /**
     * @param int $quality
     */
    public function setQuality($quality);
}
