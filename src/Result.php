<?php

namespace happyproff\Kartinki;

class Result
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $ext;

    /**
     * @var array|\string[]
     */
    protected $thumbnails = [];

    /**
     * @param string $id
     * @param string $ext
     * @param string[] $thumbnails
     */
    public function __construct($id, $ext, array $thumbnails)
    {
        $this->id = $id;
        $this->ext = $ext;
        $this->thumbnails = $thumbnails;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string[]
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }
}
