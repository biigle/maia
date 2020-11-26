<?php

namespace Biigle\Modules\Maia\Jobs;

use Storage;

class DeleteAnnotationPatchChunk extends Job
{
    /**
     * Storage disk to delete files in.
     *
     * @var string
     */
    public $disk;

    /**
     * Files to delete in the storage disk.
     *
     * @var string
     */
    public $files;

    /**
     * Create a new isntance.
     *
     * @param string $disk
     * @param array $files List of files to delete in the storage disk.
     */
    public function __construct($disk, $files)
    {
        $this->disk = $disk;
        $this->files = $files;
    }

    /**
      * Handle the event.
      *
      * @return void
      */
    public function handle()
    {
        $disk = Storage::disk($this->disk);
        foreach ($this->files as $file) {
            $disk->delete($file);
        }
    }
}
