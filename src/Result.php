<?php

namespace kartinki\Kartinki;

class Result
{
    /**
     * @var string
     */
    protected $uniqueId;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var array|\string[]
     */
    protected $thumbnails = [];

    /**
     * @param string $uniqueId
     * @param string $extension
     * @param string[] $thumbnails
     */
    public function __construct($uniqueId, $extension, array $thumbnails)
    {
        $this->uniqueId = $uniqueId;
        $this->extension = $extension;
        $this->thumbnails = $thumbnails;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return string[]
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }
}
