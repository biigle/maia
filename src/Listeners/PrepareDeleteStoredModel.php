<?php

namespace Biigle\Modules\Maia\Listeners;

use Queue;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Jobs\DeleteStoredModel;

class PrepareDeleteStoredModel
{
   /**
     * Handle the event.
     *
     * @param  MaiaJobDeleting  $event
     * @return void
     */
    public function handle(MaiaJobDeleting $event)
    {
        Queue::push(new DeleteStoredModel($event->job));
    }
}
