<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Interfaces\PresetInterface;
use happyproff\Kartinki\Interfaces\PresetParserInterface;
use happyproff\Kartinki\Exceptions\InvalidPresetException;

class PresetParser implements PresetParserInterface
{
    /**
     * @param string $preset
     *
     * @return PresetInterface
     */
    public function parse($preset)
    {
        if (!is_string($preset)) {
            throw new InvalidPresetException('$preset must be a string.');
        }

        $presetObject = new Preset;
        $parts = explode(',', $preset);
        $this->setThumbnailParams($presetObject, $parts[0]);
        if (count($parts) > 1) {
            $this->setParameters($presetObject, array_slice($parts, 1));
        }

        return $presetObject;
    }

    /**
     * @param PresetInterface $presetObject
     * @param string $presetString
     */
    protected function setThumbnailParams(PresetInterface $presetObject, $presetString)
    {
        $presetParts = [];
        if (preg_match('#(\d+)x(\d+)(:fit)*#', $presetString, $presetParts) !== 1) {
            throw new InvalidPresetException('Thumbnail preset "' . $presetString . '" is incorrect.');
        }
        $presetObject->setWidth(intval($presetParts[1]));
        $presetObject->setHeight(intval($presetParts[2]));
        if (isset($presetParts[3])) {
            if ($presetParts[3] === ':fit') {
                $presetObject->setFit(true);
            } else {
                throw new InvalidPresetException(
                    'Thumbnail preset modifier "' . $presetParts[2] . '" is incorrect. '
                );
            }
        }
    }

    /**
     * @param PresetInterface $presetObject
     * @param array $presetParameters
     */
    protected function setParameters(PresetInterface $presetObject, array $presetParameters)
    {
        foreach ($presetParameters as $parameter) {
            $parameterParts = explode('=', $parameter);
            $parameterName = $parameterParts[0];
            $parameterValue = array_key_exists(1, $parameterParts) ? $parameterParts[1] : null;
            switch ($parameterName) {
                case 'quality':
                    if ($parameterValue === null) {
                        throw new InvalidPresetException('Quality value "' . $parameterValue . '" is incorrect.');
                    } else {
                        $presetObject->setQuality(intval($parameterValue));
                    }
                    break;
                default:
                    throw new InvalidPresetException('Parameter "' . $parameterName . '" is incorrect.');
            }
        }
    }
}
