<?php

namespace Biigle\Modules\Maia\Events;

use Biigle\Broadcasting\UserChannel;
use Biigle\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnotationCandidatesConverted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param int $id The ID of the MAIA job.
     * @param User $user The user who submitted the annotation candidates.
     * @param bool $hasUnconverted Whether some candidates were not converted because of the annotation guideline.
     */
    public function __construct(
        public int $id,
        public User $user,
        public bool $hasUnconverted = false
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new UserChannel($this->user);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->id,
            'has_unconverted' => $this->hasUnconverted,
        ];
    }
}
