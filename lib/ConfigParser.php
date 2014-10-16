<?php



namespace happyproff\Kartinki;



use happyproff\Kartinki\Interfaces\ConfigInterface;
use happyproff\Kartinki\Interfaces\ConfigParserInterface;
use happyproff\Kartinki\Exceptions\InvalidConfigException;



class ConfigParser implements ConfigParserInterface {



    /**
     * @param string $config
     *
     * @return ConfigInterface
     */
    public function parse ($config) {

        $configObject = new Config;

        if (!is_string($config)) throw new InvalidConfigException('$config must be a string.');

        $parts = explode(':', $config);
        if (count($parts) == 0 or count($parts) > 2) throw new InvalidConfigException('Too match ":".');

        $dimensions = explode('x', $parts[0]);
        if (count($dimensions) !== 2) throw new InvalidConfigException('Dimensions "' . $parts[0] . '" is incorrect.');
        $configObject->setWidth(intval($dimensions[0]));
        $configObject->setHeight(intval($dimensions[1]));

        if (array_key_exists(1, $parts)) {
            if ($parts[1] !== 'fit') {
                throw new InvalidConfigException('Unknown modifier ":' . $parts[1] . '".');
            } else {
                $configObject->setFit(true);
            }
        }

        return $configObject;

    }



}
 