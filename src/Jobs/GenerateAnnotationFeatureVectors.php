<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use FileCache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use SplFileObject;

abstract class GenerateAnnotationFeatureVectors extends Job
{
    use SerializesModels;

    /**
     * Number of feature vector models to insert in one chunk.
     *
     * @var int
     */
    const INSERT_CUNK_SIZE = 1000;

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
        //
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

                if (count($insert) >= self::INSERT_CUNK_SIZE) {
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
                $box = $this->getBoundingBox($a, $image);
                $allEqual = $box[0] === $box[1] && $box[0] === $box[2] && $box[0] === $box[3];
                if (!$allEqual) {
                    $boxes[$a->id] = $box;
                }
            }

            if (!empty($boxes)) {
                $input[$path] = $boxes;
            }
        }

        return $input;
    }

    protected function getBoundingBox(MaiaAnnotation $annotation, Image $image): array
    {
        $box = match ($annotation->shape_id) {
            Shape::pointId() => $this->getPointBoundingBox($annotation),
            Shape::circleId() => $this->getCircleBoundingBox($annotation),
            // TODO: An ellipse will not be handled correctly by this but I didn't bother
            // because this shape is almost never used anyway.
            default => $this->getPolygonBoundingBox($annotation),
        };

        $box = array_map(fn ($p) => max(0, $p), $box);
        if (!is_null($image->width)) {
            $box[0] = min($image->width, $box[0]);
            $box[2] = min($image->width, $box[2]);
        }

        if (!is_null($image->height)) {
            $box[1] = min($image->height, $box[1]);
            $box[3] = min($image->height, $box[3]);
        }

        return $box;
    }

    protected function getPointBoundingBox(MaiaAnnotation $annotation): array
    {
        $points = $annotation->points;

        return [
            $points[0] - self::POINT_PADDING,
            $points[1] - self::POINT_PADDING,
            $points[0] + self::POINT_PADDING,
            $points[1] + self::POINT_PADDING,
        ];
    }

    protected function getCircleBoundingBox(MaiaAnnotation $annotation): array
    {
        $points = $annotation->points;

        return [
            $points[0] - $points[2],
            $points[1] - $points[2],
            $points[0] + $points[2],
            $points[1] + $points[2],
        ];
    }

    protected function getPolygonBoundingBox(MaiaAnnotation $annotation): array
    {
        $points = $annotation->points;
        $minX = INF;
        $minY = INF;
        $maxX = -INF;
        $maxY = -INF;

        for ($i = 0; $i < count($points); $i += 2) {
            $minX = min($minX, $points[$i]);
            $minY = min($minY, $points[$i + 1]);
            $maxX = max($maxX, $points[$i]);
            $maxY = max($maxY, $points[$i + 1]);
        }

        return [$minX, $minY, $maxX, $maxY];
    }

    /**
     * Run the Python command.
     *
     * @param string $command
     */
    protected function python(array $input, string $outputPath)
    {
        $python = config('maia.python');
        $result = Process::forever()
            ->quietly()
            ->run("{$python} -u {$command}")
            ->throw();
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
