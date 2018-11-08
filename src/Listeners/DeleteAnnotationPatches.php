<?php

namespace Biigle\Modules\Maia\Listeners;

use File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Events\MaiaJobDeleted;

class DeleteAnnotationPatches implements ShouldQueue
{
   /**
     * Handle the event.
     *
     * @param  MaiaJobDeleted  $event
     * @return void
     */
    public function handle(MaiaJobDeleted $event)
    {
        $path = config('maia.patch_storage')."/{$event->id}";

        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
    }
}
