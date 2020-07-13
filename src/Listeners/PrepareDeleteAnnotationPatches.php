<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatches;
use Queue;

class PrepareDeleteAnnotationPatches
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobDeleting  $event
      * @return void
      */
    public function handle(MaiaJobDeleting $event)
    {
        Queue::push(new DeleteAnnotationPatches($event->job));
    }
}
