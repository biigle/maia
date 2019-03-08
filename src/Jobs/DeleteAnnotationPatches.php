<?php

namespace Biigle\Modules\Maia\Jobs;

use Storage;
use Biigle\Image;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;

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
     * @param  MaiaJobDeleting  $event
     * @return void
     */
    public function handle()
    {
        $disk = Storage::disk(config('maia.training_proposal_storage_disk'));
        $this->deleteAnnotations($this->trainingProposals, $disk);

        $disk = Storage::disk(config('maia.annotation_candidate_storage_disk'));
        $this->deleteAnnotations($this->annotationCandidates, $disk);
    }

    /**
     * Delete MAIA annotations form the specified storage disk.
     *
     * @param array $annotations
     * @param FilesystemAdapter $disk
     */
    protected function deleteAnnotations($annotations, $disk)
    {
        $format = config('largo.patch_format');

        // Process chunks of 100 images.
        foreach (array_chunk($annotations, 100, true) as $chunk) {
            $uuids = Image::whereIn('id', array_keys($chunk))->pluck('uuid', 'id');
            foreach ($chunk as $imageId => $annotationIds) {
                $prefix = fragment_uuid_path($uuids[$imageId]);
                foreach ($annotationIds as $id) {
                    $disk->delete("{$prefix}/{$id}.{$format}");
                }
            }
        }
    }

    /**
     * Get the array of annotation IDs grouped by image IDs.
     *
     * @param QueryBuilder $query
     *
     * @return array
     */
    protected function getAnnotationsArray($query)
    {
        $ids = [];

        $query->pluck('image_id', 'id')->each(function ($imageId, $id) use (&$ids) {
            $ids[$imageId][] = $id;
        });

        return $ids;
    }
}
