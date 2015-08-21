<?php

namespace kartinki\Kartinki\Interfaces;

interface PresetParserInterface
{
    /**
     * @param string $preset
     *
     * @return PresetInterface
     */
    public function parse($preset);
}
