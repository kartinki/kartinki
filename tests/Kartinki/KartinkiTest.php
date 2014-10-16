<?php



namespace happyproff\Kartinki;



class KartinkiTest extends \PHPUnit_Framework_TestCase {



    private static $assetsDir;
    private static $tempDir;



    public static function setUpBeforeClass () {

        self::$assetsDir = dirname(dirname(__FILE__)) . '/assets';
        self::$tempDir = self::$assetsDir . '/tmp';

    }



    public function testVersionsCreating () {

        $this->_testImage('bigh.jpg');
        $this->_testImage('bigv.jpg');
        $this->_testImage('smallh.png');

    }



    private function prepareTempDir () {

        if (is_dir(self::$tempDir)) $this->removeTempDir();
        mkdir(self::$tempDir);

    }



    private function removeTempDir () {

        foreach (scandir(self::$tempDir) as $file) {
            if (in_array($file, ['.', '..'])) continue;
            unlink(self::$tempDir . '/' . $file);
        }
        rmdir(self::$tempDir);

    }



    private function _testImage ($imageName) {

        $this->prepareTempDir();

        $versions = [
            'thumb' => ['width' => 200, 'height' => 200, 'fit' => false, 'quality' => 0],
            'vertical' => ['width' => 200, 'height' => 0, 'fit' => false],
            'horizontal' => ['width' => 0, 'height' => 200, 'fit' => false],
            'big' => ['width' => 400, 'height' => 400, 'fit' => true],
            'orig' => ['width' => 0, 'height' => 0, 'fit' => false],
        ];
        $versionsConfig = array_map(function($value){
            return $value['width'] . 'x' . $value['height'] . ($value['fit'] ? ':fit' : '');
        }, $versions);


        $imagePath = self::$assetsDir . '/' . $imageName;
        list($initialWidth, $initialHeight) = getimagesize($imagePath);

        $kartinki = new Kartinki;
        $result = $kartinki->createImageVersions($imagePath, $versionsConfig, self::$tempDir);
        foreach ($versions as $versionName => $versionConfig) {
            $this->assertArrayHasKey($versionName, $result);
            $this->assertFileExists(self::$tempDir . '/' . $result[$versionName]);
            list($width, $height) = getimagesize(self::$tempDir . '/' . $result[$versionName]);

            if (!$versionConfig['fit']) {
                if ($versionConfig['width']) $this->assertEquals($versionConfig['width'], $width);
                if ($versionConfig['height']) $this->assertEquals($versionConfig['height'], $height);
            }
        }

        list($width, $height) = getimagesize(self::$tempDir . '/' . $result['big']);
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
 