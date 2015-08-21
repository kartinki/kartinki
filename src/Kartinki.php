<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Interfaces\PresetInterface;
use happyproff\Kartinki\Interfaces\PresetParserInterface;
use happyproff\Kartinki\Exceptions\FileIsNotReadable;
use happyproff\Kartinki\Exceptions\OutputDirectoryIsNotWritable;
use happyproff\Kartinki\Exceptions\InvalidArgumentException;
use happyproff\Kartinki\Exceptions\FileNotFoundException;
use happyproff\Kartinki\Exceptions\InvalidPresetException;
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
     * @var PresetParser
     */
    protected $presetParser;

    public function __construct(ImagineInterface $imagine = null, $presetParser = null)
    {
        if ($presetParser !== null && !($presetParser instanceof PresetParserInterface)) {
            throw new InvalidArgumentException('$presetParser must implement happyproff\Kartinki\Interfaces\PresetParserInterface.');
        }
        $this->processor = $imagine ?: new Imagine;
        $this->presetParser = $presetParser ?: new PresetParser;
    }

    /**
     * @param string $imagePath
     * @param string[]|PresetInterface[] $presets
     * @param string $outputDir
     * @param string $imageUniqueName
     *
     * @return Result
     */
    public function createThumbnails($imagePath, $presets, $outputDir = null, $imageUniqueName = null)
    {
        if (!file_exists($imagePath)) {
            throw new FileNotFoundException("File '{$imagePath}' not exists.");
        }
        if (!is_readable($imagePath)) {
            throw new FileIsNotReadable("File '{$imagePath}' is not readable.");
        }
        if (is_null($outputDir)) {
            $outputDir = $this->outputDir ? $this->outputDir : realpath(dirname($imagePath));
        }
        if (!is_writable($outputDir)) {
            throw new OutputDirectoryIsNotWritable("Ouput directory '{$outputDir}' is not writable.");
        }
        if (is_null($imageUniqueName)) {
            $imageUniqueName = $this->getUniqueName($imagePath);
        } elseif (!is_string($imageUniqueName) and !(is_object($imageUniqueName) and method_exists($imageUniqueName, '__toString'))) {
            throw new InvalidArgumentException("$imageUniqueName must be a string.");
        }

        $thumbnails = [];
        $imageExt = pathinfo($imagePath, PATHINFO_EXTENSION);

        foreach ($presets as $presetName => $preset) {
            if (is_string($preset)) {
                $parsedPreset = $this->presetParser->parse($preset);
            } elseif ($preset instanceof PresetInterface) {
                $parsedPreset = $preset;
            } else {
                throw new InvalidArgumentException('Preset must be a string or implements happyproff\Kartinki\Interfaces\PresetInterface.');
            }

            $thumbnailFilename = $imageUniqueName . self::NAME_SEPARATOR . $presetName . '.' . $imageExt;
            $image = $this->processor->read(fopen($imagePath, 'r'));

            $thumbnail = $this->createImageThumbnail($image, $parsedPreset);
            $thumbnail->save($outputDir . '/' . $thumbnailFilename, ['jpeg_quality' => $parsedPreset->getQuality()]);
            unset($thumbnail);

            $thumbnails[$presetName] = $thumbnailFilename;
        }

        $result = new Result($imageUniqueName, $imageExt, $thumbnails);

        return $result;
    }

    /**
     * @param ImageInterface $image
     * @param PresetInterface $preset
     *
     * @return ImageInterface
     */
    protected function createImageThumbnail(ImageInterface $image, PresetInterface $preset)
    {
        $width = $preset->getWidth();
        if ($width === 0) {
            $width = PHP_INT_MAX;
        }

        $height = $preset->getHeight();
        if ($height === 0) {
            $height = PHP_INT_MAX;
        }

        $image = $image->thumbnail(
            new Box($width, $height),
            $preset->isFit() ? ImageInterface::THUMBNAIL_INSET : ImageInterface::THUMBNAIL_OUTBOUND
        );

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
