<?php

namespace Biigle\Modules\Maia;

use Biigle\FileCache\GenericFile;

class GenericImage extends GenericFile
{
    /**
     * The image ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Create a new instance.
     *
     * @param int $id
     * @param string $url
     */
    public function __construct($id, $url)
    {
        parent::__construct($url);
        $this->id = $id;
    }
    /**
     * Get the image ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
