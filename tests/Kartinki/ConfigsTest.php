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

        $config = null;
        try {
            $config = $configParser->parse('');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);

        try {
            $config = $configParser->parse('abcs');
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
            $config = $configParser->parse('200x300:fit:rotate');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidConfigException', $e);
        }
        $this->assertEquals(null, $config);
    }
}
