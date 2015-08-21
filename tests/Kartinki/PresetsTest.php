<?php

namespace happyproff\Kartinki;

class PresetsTest extends \PHPUnit_Framework_TestCase
{
    public function testInstancesCreation()
    {
        $this->assertInstanceOf('happyproff\Kartinki\PresetParser', new PresetParser);
        $this->assertInstanceOf('happyproff\Kartinki\Preset', new Preset);
    }

    /**
     * @depends testInstancesCreation
     */
    public function testPresetParser()
    {
        $presetParser = new PresetParser;

        $preset = $presetParser->parse('200x300');
        $this->assertInstanceOf('happyproff\Kartinki\Preset', $preset);
        $this->assertEquals(200, $preset->getWidth());
        $this->assertEquals(300, $preset->getHeight());
        $this->assertEquals(false, $preset->isFit());

        $preset = $presetParser->parse('0x1920:fit');
        $this->assertInstanceOf('happyproff\Kartinki\Preset', $preset);
        $this->assertEquals(0, $preset->getWidth());
        $this->assertEquals(1920, $preset->getHeight());
        $this->assertEquals(true, $preset->isFit());

        $preset = $presetParser->parse('200x0,quality=60');
        $this->assertInstanceOf('happyproff\Kartinki\Preset', $preset);
        $this->assertEquals(200, $preset->getWidth());
        $this->assertEquals(0, $preset->getHeight());
        $this->assertEquals(false, $preset->isFit());
        $this->assertEquals(60, $preset->getQuality());

        $preset = $presetParser->parse('300x250:fit,quality=99');
        $this->assertInstanceOf('happyproff\Kartinki\Preset', $preset);
        $this->assertEquals(300, $preset->getWidth());
        $this->assertEquals(250, $preset->getHeight());
        $this->assertEquals(true, $preset->isFit());
        $this->assertEquals(99, $preset->getQuality());

        $preset = $presetParser->parse('300x250,quality=10,quality=20');
        $this->assertInstanceOf('happyproff\Kartinki\Preset', $preset);
        $this->assertEquals(300, $preset->getWidth());
        $this->assertEquals(250, $preset->getHeight());
        $this->assertEquals(false, $preset->isFit());
        $this->assertEquals(20, $preset->getQuality());

        $preset = null;
        try {
            $preset = $presetParser->parse('');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidPresetException', $e);
        }
        $this->assertEquals(null, $preset);

        try {
            $preset = $presetParser->parse('wrong');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidPresetException', $e);
        }
        $this->assertEquals(null, $preset);

        try {
            $preset = $presetParser->parse('200x300:fit,rotate');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidPresetException', $e);
        }
        $this->assertEquals(null, $preset);

        try {
            $preset = $presetParser->parse('200x300:fit,quality');
        } catch (\Exception $e) {
            $this->assertInstanceOf('happyproff\Kartinki\Exceptions\InvalidPresetException', $e);
        }
        $this->assertEquals(null, $preset);
    }
}
