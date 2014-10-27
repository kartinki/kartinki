<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Interfaces\ConfigInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

class Kartinki
{
    const NAME_SEPARATOR = '_';
    /**
     * @var string
     */
    public $outputDir;
    /**
     * @var ImagineInterface
     */
    protected $processor;
    /**
     * @var ConfigParser
     */
    protected $configParser;

    public function __construct(ImagineInterface $imagine = null, $configParser = null)
    {
        $this->processor = $imagine ?: new Imagine;
        $this->configParser = $configParser ?: new ConfigParser;
    }

    /**
     * @param string $imagePath
     * @param string[] $versionsConfig
     * @param string $outputDir
     * @param string $imageUniqueName
     *
     * @return string[]
     */
    public function createImageVersions($imagePath, $versionsConfig, $outputDir = null, $imageUniqueName = null)
    {
        // TODO: check image exists and is_readable
        // TODO: outputDir is writable
        if ($outputDir === null) {
            $outputDir = $this->outputDir;
        }
        if ($imageUniqueName === null) {
            $imageUniqueName = $this->getUniqueName($imagePath);
        }

        $versions = [];
        $imageExt = pathinfo($imagePath, PATHINFO_EXTENSION);

        foreach ($versionsConfig as $versionName => $versionConfig) {
            $versionFilename = $imageUniqueName . self::NAME_SEPARATOR . $versionName . '.' . $imageExt;
            $image = $this->processor->read(fopen($imagePath, 'r'));
            $config = $this->configParser->parse($versionConfig);

            $version = $this->createImageVersion($image, $config);
            $version->save($outputDir . '/' . $versionFilename);
            unset($version);

            $versions[$versionName] = $versionFilename;
        }

        return $versions;
    }

    /**
     * @param ImageInterface $image
     * @param ConfigInterface $versionConfig
     *
     * @return ImageInterface
     */
    protected function createImageVersion(ImageInterface $image, ConfigInterface $versionConfig)
    {
        $width = $versionConfig->getWidth();
        if ($width === 0) {
            $width = PHP_INT_MAX;
        }

        $height = $versionConfig->getHeight();
        if ($height === 0) {
            $height = PHP_INT_MAX;
        }

        $image = $image->thumbnail(new Box($width, $height),
            $versionConfig->isFit() ? ImageInterface::THUMBNAIL_INSET : ImageInterface::THUMBNAIL_OUTBOUND);

        return $image;
    }

    /**
     * @param string $imagePath
     *
     * @return string
     */
    protected function getUniqueName($imagePath)
    {
        $uniqueName = md5(uniqid($imagePath, true));

        return $uniqueName;
    }
}
