<?php

namespace kartinki\Kartinki\Tests;

use kartinki\Kartinki\Interfaces\PresetInterface;
use kartinki\Kartinki\Preset;
use kartinki\Kartinki\PresetParser;
use kartinki\Kartinki\Thumbnailer;

class ThumbnailerTest extends \PHPUnit_Framework_TestCase
{
    private static $assetsDir;
    private static $tempDir;

    public static function setUpBeforeClass()
    {
        self::$assetsDir = dirname(dirname(__FILE__)) . '/assets';
        self::$tempDir = self::$assetsDir . '/tmp';
    }

    public function testThumbnailsCreating()
    {
        $this->_testImage('big-horizontal.jpg');
        $this->_testImage('big-vertical.jpg');
        $this->_testImage('small-horizontal.png');
    }

    public function testManuallyCreatedPresets()
    {
        $preset = new Preset;
        $preset->setWidth(400);
        $preset->setHeight(400);
        $preset->setFit(true);
        $this->_testManuallyCreatedPreset($preset);

        $preset = (new Preset)->setWidth(400)->setHeight(400)->setFit(true);
        $this->_testManuallyCreatedPreset($preset);

        $preset = Preset::createFromArray([
            'width' => 400,
            'height' => 400,
            'fit' => true,
        ]);
        $this->_testManuallyCreatedPreset($preset);

        $preset = (new PresetParser)->parse('400x400:fit');
        $this->_testManuallyCreatedPreset($preset);

        $preset = new Preset(400, 400);
        $preset->setFit(true);
        $this->_testManuallyCreatedPreset($preset);
    }

    public function testCustomOutputDirectory()
    {
        $customTempDir = self::$tempDir . '_custom';
        $this->prepareTempDir($customTempDir);

        $result = (new Thumbnailer)->createThumbnails(
            self::$assetsDir . '/big-vertical.jpg',
            [
                'big' => '800x600:fit',
                'small' => '150x150',
            ],
            $customTempDir
        );

        foreach ($result->getThumbnails() as $filename) {
            $filepath = $customTempDir . '/' . $filename;
            $this->assertFileExists($filepath);
        }

        $this->removeTempDir($customTempDir);
    }

    public function testIdAndExtensionInResult()
    {
        $this->prepareTempDir();

        $result = (new Thumbnailer)->createThumbnails(
            self::$assetsDir . '/big-vertical.jpg',
            [
                'big' => '800x600:fit',
                'small' => '150x150',
            ],
            self::$tempDir
        );

        $filename = $result->getThumbnails()['big'];
        $expectedId = str_replace('_big.jpg', '', $filename);
        $expectedExt = pathinfo($filename, PATHINFO_EXTENSION);

        $this->assertEquals($expectedId, $result->getUniqueId());
        $this->assertEquals($expectedExt, $result->getExtension());

        $this->removeTempDir();
    }

    /**
     * @expectedException kartinki\Kartinki\Exceptions\FileNotFoundException
     */
    public function testFileNotExistsException()
    {
        $this->prepareTempDir();

        (new Thumbnailer)->createThumbnails('unknown file.jpg', ['big' => '800x600:fit']);

        $this->removeTempDir();
    }

    /**
     * @expectedException kartinki\Kartinki\Exceptions\DirectoryIsNotWritableException
     */
    public function testDirectoryIsNotWritableException()
    {
        $this->prepareTempDir();

        (new Thumbnailer)->createThumbnails(self::$assetsDir . '/big-horizontal.jpg', ['big' => '800x600:fit'], dirname(__FILE__) . '/unknown_dir');

        $this->removeTempDir();
    }

    private function _testManuallyCreatedPreset(PresetInterface $preset)
    {
        $this->prepareTempDir();

        $presets = [
            'big' => $preset,
        ];

        $imagePath = self::$assetsDir . '/big-horizontal.jpg';

        $kartinki = new Thumbnailer;
        $result = $kartinki->createThumbnails($imagePath, $presets, self::$tempDir);

        $this->assertArrayHasKey('big', $result->getThumbnails());
        $this->assertFileExists(self::$tempDir . '/' . $result->getThumbnails()['big']);
        list($width, $height) = getimagesize(self::$tempDir . '/' . $result->getThumbnails()['big']);
        $this->assertEquals(400, $width);
        $this->assertEquals(225, $height);

        $this->removeTempDir();
    }

    private function prepareTempDir($tempDir = null)
    {
        if (is_null($tempDir)) {
            $tempDir = self::$tempDir;
        }
        if (is_dir($tempDir)) {
            $this->removeTempDir($tempDir);
        }
        mkdir($tempDir);
    }

    private function removeTempDir($tempDir = null)
    {
        if (is_null($tempDir)) {
            $tempDir = self::$tempDir;
        }
        foreach (scandir($tempDir) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            unlink($tempDir . '/' . $file);
        }
        rmdir($tempDir);
    }

    private function _testImage($imageName)
    {
        $this->prepareTempDir();

        $presetsArray = [
            'thumb' => ['width' => 200, 'height' => 200, 'fit' => false, 'quality' => 10],
            'vertical' => ['width' => 200, 'height' => 0, 'fit' => false],
            'horizontal' => ['width' => 0, 'height' => 200, 'fit' => false],
            'big' => ['width' => 400, 'height' => 400, 'fit' => true],
            'orig' => ['width' => 0, 'height' => 0, 'fit' => false],
        ];
        $presets = array_map(function ($value) {
            return $value['width'] . 'x' . $value['height'] . ($value['fit'] ? ':fit' : '') . (isset($value['quality']) ? ',quality=' . $value['quality'] : '');
        }, $presetsArray);

        $imagePath = self::$assetsDir . '/' . $imageName;
        list($initialWidth, $initialHeight) = getimagesize($imagePath);

        $kartinki = new Thumbnailer;
        $result = $kartinki->createThumbnails($imagePath, $presets, self::$tempDir);
        foreach ($presetsArray as $presetName => $presetString) {
            $this->assertArrayHasKey($presetName, $result->getThumbnails());
            $this->assertFileExists(self::$tempDir . '/' . $result->getThumbnails()[$presetName]);
            list($width, $height) = getimagesize(self::$tempDir . '/' . $result->getThumbnails()[$presetName]);

            if (!$presetString['fit']) {
                if ($presetString['width']) {
                    $this->assertEquals($presetString['width'], $width);
                }
                if ($presetString['height']) {
                    $this->assertEquals($presetString['height'], $height);
                }
            }
        }

        list($width, $height) = getimagesize(self::$tempDir . '/' . $result->getThumbnails()['big']);
        if ($initialWidth > $initialHeight) {
            $this->assertEquals(400, $width);
            $this->assertEquals(($initialHeight / $initialWidth * 400), $height);
        } else {
            $this->assertEquals(400, $height);
            $this->assertEquals(($initialWidth / $initialHeight * 400), $width);
        }

        $this->removeTempDir();
    }
}
