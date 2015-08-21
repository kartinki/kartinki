<?php

namespace kartinki\Kartinki;

use kartinki\Kartinki\Interfaces\PresetInterface;
use kartinki\Kartinki\Interfaces\PresetParserInterface;
use kartinki\Kartinki\Exceptions\FileIsNotReadable;
use kartinki\Kartinki\Exceptions\OutputDirectoryIsNotWritable;
use kartinki\Kartinki\Exceptions\InvalidArgumentException;
use kartinki\Kartinki\Exceptions\FileNotFoundException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

class Thumbnailer
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
            throw new InvalidArgumentException('$presetParser must implement kartinki\Kartinki\Interfaces\PresetParserInterface.');
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
        } elseif (!is_string($imageUniqueName) && !(is_object($imageUniqueName) && method_exists($imageUniqueName, '__toString'))) {
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
                throw new InvalidArgumentException('Preset must be a string or implements kartinki\Kartinki\Interfaces\PresetInterface.');
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
