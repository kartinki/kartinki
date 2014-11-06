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

        $thumbnailConfigParts = [];
        if (preg_match('#(\d+)x(\d+)(:fit)*#', $parts[0], $thumbnailConfigParts) !== 1) {
            throw new InvalidConfigException('Thumbnail config "' . $parts[0] . '" is incorrect.');
        }
        $configObject->setWidth(intval($thumbnailConfigParts[1]));
        $configObject->setHeight(intval($thumbnailConfigParts[2]));
        if (isset($thumbnailConfigParts[3])) {
            if ($thumbnailConfigParts[3] === ':fit') {
                $configObject->setFit(true);
            } else {
                throw new InvalidConfigException('Thumbnail config modifier "' . $thumbnailConfigParts[2] . '" is incorrect. ');
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
