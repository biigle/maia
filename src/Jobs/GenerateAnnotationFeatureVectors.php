<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Jobs\Job;
use Biigle\Modules\Largo\Traits\ComputesAnnotationBox;
use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use FileCache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use SplFileObject;

abstract class GenerateAnnotationFeatureVectors extends Job implements ShouldQueue
{
    use SerializesModels, ComputesAnnotationBox;

    /**
     * Number of feature vector models to insert in one chunk.
     *
     * @var int
     */
    const INSERT_CHUNK_SIZE = 1000;

    /**
     * The "radius" of the bounding box around a point annotation.
     *
     * This is half the patch size of 224 that is expected by DINO.
     *
     * @var int
     */
    const POINT_PADDING = 112;

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
            ->with('volume')
            ->get()
            ->all();

        $outputPath = tempnam(sys_get_temp_dir(), 'maia_feature_vector_output');

        try {
            FileCache::batch($images, function ($images, $paths) use ($annotations, $outputPath) {
                $input = $this->generateInput($images, $paths, $annotations);
                if (!empty($input)) {
                    $this->python($input, $outputPath);
                }
            });

            $insert = [];
            foreach ($this->readOuputCsv($outputPath) as $row) {
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

    /**
     * Generate the input for the python script.
     *
     * @param \Illuminate\Support\Collection $annotations
     */
    protected function generateInput(array $images, array $paths, $annotations): array
    {
        $annotations = $annotations->groupBy('image_id');
        $input = [];

        foreach ($images as $index => $image) {
            $path = $paths[$index];
            $imageAnnotations = $annotations[$image->id];
            $boxes = [];
            foreach ($imageAnnotations as $a) {
                $box = $this->getAnnotationBoundingBox($a->points, $a->shape, self::POINT_PADDING);
                $box = $this->makeBoxContained($box, $image->width, $image->height);
                $zeroSize = $box[2] === 0 && $box[3] === 0;

                if (!$zeroSize) {
                    // Convert width and height to "right" and "bottom" coordinates.
                    $box[2] = $box[0] + $box[2];
                    $box[3] = $box[1] + $box[3];

                    $boxes[$a->id] = $box;
                }
            }

            if (!empty($boxes)) {
                $input[$path] = $boxes;
            }
        }

        return $input;
    }

    /**
     * Run the Python command.
     *
     * @param string $command
     */
    protected function python(array $input, string $outputPath)
    {
        $python = config('maia.python');
        $script = config('maia.extract_features_script');
        $inputPath = tempnam(sys_get_temp_dir(), 'maia_feature_vector_input');
        File::put($inputPath, json_encode($input));
        try {
            $result = Process::forever()
                ->env(['TORCH_HOME' => config('maia.torch_hub_path')])
                ->run("{$python} -u {$script} {$inputPath} {$outputPath}")
                ->throw();
        } finally {
            File::delete($inputPath);
        }
    }

    /**
     * Generator to read the output CSV row by row.
     */
    protected function readOuputCsv(string $path): \Generator
    {
        $file = new SplFileObject($path);
        while (!$file->eof()) {
            $csv = $file->fgetcsv();
            if (count($csv) === 2) {
                yield $csv;
            }
        }
    }
}
