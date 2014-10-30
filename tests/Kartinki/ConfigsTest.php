<?php

namespace happyproff\Kartinki;

class ConfigsTest extends \PHPUnit_Framework_TestCase
{
    public function testInstancesCreation()
    {
        $this->assertInstanceOf('happyproff\Kartinki\ConfigParser', new ConfigParser);
        $this->assertInstanceOf('happyproff\Kartinki\Config', new Config);
    }

    /**
     * @depends testInstancesCreation
     */
    public function testConfigParser()
    {
        $configParser = new ConfigParser;

        $config = $configParser->parse('200x300');
        $this->assertInstanceOf('happyproff\Kartinki\Config', $config);
        $this->assertEquals(200, $config->getWidth());
        $this->assertEquals(300, $config->getHeight());
        $this->assertEquals(false, $config->isFit());

        $config = $configParser->parse('0x1920:fit');
        $this->assertInstanceOf('happyproff\Kartinki\Config', $config);
        $this->assertEquals(0, $config->getWidth());
        $this->assertEquals(1920, $config->getHeight());
        $this->assertEquals(true, $config->isFit());

        $config = $configParser->parse('200x0,quality=60');
        $this->assertInstanceOf('happyproff\Kartinki\Config', $config);
        $this->assertEquals(200, $config->getWidth());
        $this->assertEquals(0, $config->getHeight());
        $this->assertEquals(false, $config->isFit());
        $this->assertEquals(60, $config->getQuality());

        $config = $configParser->parse('300x250:fit,quality=99');
        $this->assertInstanceOf('happyproff\Kartinki\Config', $config);
        $this->assertEquals(300, $config->getWidth());
        $this->assertEquals(250, $config->getHeight());
        $this->assertEquals(true, $config->isFit());
        $this->assertEquals(99, $config->getQuality());

        $config = $configParser->parse('300x250,quality=10,quality=20');
        $this->assertInstanceOf('happyproff\Kartinki\Config', $config);
        $this->assertEquals(300, $config->getWidth());
        $this->assertEquals(250, $config->getHeight());
        $this->assertEquals(false, $config->isFit());
        $this->assertEquals(20, $config->getQuality());

        $config = null;
        try {
            $config = $configParser->parse('');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);

        try {
            $config = $configParser->parse('wrong');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);

        try {
            $config = $configParser->parse('200x300x400');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);

        try {
            $config = $configParser->parse('200x300:fit,rotate');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);

        try {
            $config = $configParser->parse('200x300:fit,quality');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);
    }
}
