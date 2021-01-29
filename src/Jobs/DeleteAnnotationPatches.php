<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\MaiaJob;
use Queue;
use Storage;

class DeleteAnnotationPatches extends Job
{
    /**
     * The training proposals to delete, grouped by image ID.
     *
     * @var array
     */
    protected $trainingProposals;

    /**
     * The annotation candidates to delete, grouped by image ID.
     *
     * @var array
     */
    protected $annotationCandidates;

    /**
     * Create a new isntance.
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        // Do not serialize the MAIA job and get the annotations later because when this
        // job is handled, the MAIA job and its annotations have been deleted.
        $this->trainingProposals = $this->getAnnotationsArray($job->trainingProposals());
        $this->annotationCandidates = $this->getAnnotationsArray($job->annotationCandidates());
    }

    /**
      * Handle the event.
      *
      * @return void
      */
    public function handle()
    {
        $disk = config('maia.training_proposal_storage_disk');
        $this->deleteAnnotations($this->trainingProposals, $disk);

        $disk = config('maia.annotation_candidate_storage_disk');
        $this->deleteAnnotations($this->annotationCandidates, $disk);
    }

    /**
     * Delete MAIA annotations form the specified storage disk.
     *
     * @param array $annotations
     * @param string $disk
     */
    protected function deleteAnnotations($annotations, $disk)
    {
        $format = config('largo.patch_format', 'jpg');

        // Process chunks of 100 annotations.
        foreach ($annotations->chunk(100) as $chunk) {
            $uuids = Image::whereIn('id', $chunk->values())->pluck('uuid', 'id');
            $files = $chunk->map(function ($imageId, $annotationId) use ($uuids, $format) {
                $prefix = fragment_uuid_path($uuids[$imageId]);

                return "{$prefix}/{$annotationId}.{$format}";
            })->values()->toArray();

            Queue::push(new DeleteAnnotationPatchChunk($disk, $files));
        }
    }

    /**
     * Get the array of annotation IDs and their image ids.
     *
     * @param QueryBuilder $query
     *
     * @return array
     */
    protected function getAnnotationsArray($query)
    {
        return $query->pluck('image_id', 'id');
    }
}
