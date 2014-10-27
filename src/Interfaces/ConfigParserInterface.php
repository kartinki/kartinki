<?php



namespace happyproff\Kartinki\Interfaces;



interface ConfigParserInterface {



    /**
     * @param string $config
     *
     * @return ConfigInterface
     */
    public function parse ($config);



}
 