<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Jobs\Job;
use Biigle\Modules\Largo\Jobs\GenerateFeatureVectors;
use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use FileCache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

abstract class GenerateAnnotationFeatureVectors extends GenerateFeatureVectors
{
    use SerializesModels;

    /**
     * Number of feature vector models to insert in one chunk.
     *
     * @var int
     */
    const INSERT_CHUNK_SIZE = 1000;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

    /**
     * Create a new isntance.
     */
    public function __construct(public MaiaJob $job)
    {
        $this->onQueue(config('maia.feature_vector_queue'));
        $this->onConnection(config('maia.job_connection'));
    }

    /**
      * Handle the job.
      *
      * @return void
      */
    public function handle()
    {
        $annotations = $this->getAnnotations();
        $imageIds = $annotations->pluck('image_id')->unique();
        $images = Image::whereIn('id', $imageIds)
            ->with('volume') // Required to efficiently determine the full image URL.
            ->get()
            ->all();

        $inputPath = tempnam(sys_get_temp_dir(), 'maia_feature_vector_input');
        $outputPath = tempnam(sys_get_temp_dir(), 'maia_feature_vector_output');

        try {
            FileCache::batch($images, function ($images, $paths) use ($annotations, $inputPath, $outputPath) {
                $annotations = $annotations->groupBy('image_id');
                $input = $this->generateInput($images, $paths, $annotations);
                if (!empty($input)) {
                    File::put($inputPath, json_encode($input));
                    $this->python($inputPath, $outputPath);
                }
            });

            $insert = [];
            foreach ($this->readOutputCsv($outputPath) as $row) {
                $insert[] = [
                    'id' => $row[0],
                    'job_id' => $this->job->id,
                    'vector' => $row[1],
                ];

                if (count($insert) >= self::INSERT_CHUNK_SIZE) {
                    $this->insertFeatureVectorModelChunk($insert);
                    $insert = [];
                }
            }

            // Insert remaining models.
            $this->insertFeatureVectorModelChunk($insert);
        } finally {
            File::delete($outputPath);
            File::delete($inputPath);
        }
    }

    /**
     * Get the training proposals or annotation candidates of the job.
     *
     * @return \Illuminate\Support\Collection
     */
    abstract protected function getAnnotations();

    /**
     * Insert a chunk of new feature vector models.
     */
    abstract protected function insertFeatureVectorModelChunk(array $chunk): void;
}
