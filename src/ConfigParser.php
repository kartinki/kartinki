<?php

namespace happyproff\Kartinki;

use happyproff\Kartinki\Exceptions\InvalidConfigException;
use happyproff\Kartinki\Interfaces\ConfigInterface;
use happyproff\Kartinki\Interfaces\ConfigParserInterface;

class ConfigParser implements ConfigParserInterface
{
    /**
     * @param string $config
     *
     * @return ConfigInterface
     */
    public function parse($config)
    {
        $configObject = new Config;

        if (!is_string($config)) {
            throw new InvalidConfigException('Config must be a string.');
        }

        $parts = explode(',', $config);

        /**
         * thumbnailConfig      DIMENSIONS[:fit]
         *  DIMENSIONS          WIDTHxHEIGHT
         */
        $thumbnailConfigParts = explode(':', $parts[0]);
        if (count($thumbnailConfigParts) > 2) {
            throw new InvalidConfigException('Thumbnail config "' . $parts[0] . '" is incorrect.');
        }

        $thumbnailConfigDimensions = explode('x', $thumbnailConfigParts[0]);
        if (count($thumbnailConfigDimensions) !== 2) {
            throw new InvalidConfigException('Thumbnail config dimensions "' . $thumbnailConfigParts[0] . '" is incorrect.');
        }
        $configObject->setWidth(intval($thumbnailConfigDimensions[0]));
        $configObject->setHeight(intval($thumbnailConfigDimensions[1]));

        if (isset($thumbnailConfigParts[1])) {
            if ($thumbnailConfigParts[1] === 'fit') {
                $configObject->setFit(true);
            } else {
                throw new InvalidConfigException('Thumbnail config modifier "' . $thumbnailConfigParts[1] . '" is incorrect.');
            }
        }


        if (count($parts) > 1) {
            foreach (array_slice($parts, 1) as $parameter) {
                $parameterParts = explode('=', $parameter);
                $parameterName = $parameterParts[0];
                $parameterValue = array_key_exists(1, $parameterParts) ? $parameterParts[1] : null;
                switch ($parameterName) {
                    case 'quality':
                        if ($parameterValue === null) {
                            throw new InvalidConfigException('Quality value "' . $parameterValue . '" is incorrect.');
                        } else {
                            $configObject->setQuality(intval($parameterValue));
                        }
                        break;
                    default:
                        throw new InvalidConfigException('Parameter "' . $parameterName . '" is incorrect.');
                }
            }
        }

        return $configObject;
    }
}
