<?php

namespace Biigle\Modules\Maia\Listeners;

use Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;

class DeleteAnnotationPatches implements ShouldQueue
{
   /**
     * Handle the event.
     *
     * @param  MaiaJobDeleting  $event
     * @return void
     */
    public function handle(MaiaJobDeleting $event)
    {
        $disk = Storage::disk(config('maia.training_proposal_storage_disk'));
        $this->deleteAnnotations($event->job->trainingProposals(), $disk);

        $disk = Storage::disk(config('maia.annotation_candidate_storage_disk'));
        $this->deleteAnnotations($event->job->annotationCandidates(), $disk);
    }

    /**
     * Delete MAIA annotations form the specified storage disk.
     *
     * @param QueryBuilder $query
     * @param FilesystemAdapter $disk
     */
    protected function deleteAnnotations($query, $disk)
    {
        $format = config('largo.patch_format');

        $query->with('image')->chunkById(10000, function ($chunk) use ($disk, $format) {
            foreach ($chunk as $annotation) {
                $prefix = fragment_uuid_path($annotation->image->uuid);
                $disk->delete("{$prefix}/{$annotation->id}.{$format}");
            }
        });
    }
}
