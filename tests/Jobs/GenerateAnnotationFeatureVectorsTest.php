<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\Shape;
use Exception;
use File;
use FileCache;
use TestCase;

class GenerateAnnotationFeatureVectorsTest extends TestCase
{
    public function testHandle()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 5],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        $job->output = [[$tp->id, '"'.json_encode(range(0, 383)).'"']];
        try {
            $job->handle();

            $this->assertFalse(File::exists($job->outputPath));
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([5, 5, 15, 15], $box);

        $vectors = TrainingProposalFeatureVector::where('job_id', $j->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertEquals($tp->id, $vectors[0]->id);
        $this->assertEquals(range(0, 383), $vectors[0]->vector->toArray());
    }

    public function testHandleConvertPoint()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::pointId(),
            'points' => [300, 300],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([188, 188, 412, 412], $box);
    }

    public function testHandleConvertRectangle()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::rectangleId(),
            'points' => [10, 10, 20, 10, 20, 20, 10, 20],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([10, 10, 20, 20], $box);
    }

    public function testHandleConvertLineString()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::lineId(),
            'points' => [10, 10, 20, 20],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([10, 10, 20, 20], $box);
    }

    public function testHandleConvertEllipse()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::ellipseId(),
            'points' => [10, 10, 20, 10, 20, 20, 10, 20],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([10, 10, 20, 20], $box);
    }

    public function testHandleConvertPolygon()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::polygonId(),
            'points' => [10, 10, 20, 10, 15, 20, 10, 10],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([10, 10, 20, 20], $box);
    }

    public function testHandleCoodinatesOutsideImageNegative()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 15],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([0, 0, 25, 25], $box);
    }

    public function testHandleCoodinatesOutsideImagePositive()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $image = Image::factory()->create([
            'attrs' => ['width' => 100, 'height' => 100],
            'volume_id' => $j->volume_id,
        ]);
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [90, 90, 15],
            'image_id' => $image->id,
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($tp->id, $input[$filename]);
        $box = $input[$filename][$tp->id];
        $this->assertEquals([75, 75, 100, 100], $box);
    }

    public function testHandleCoodinatesSkipZero()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 0],
        ]);

        $job = new GenerateAnnotationFeatureVectorsStub($j);
        try {
            $job->handle();
        } finally {
            if (isset($job->outputPath) && File::exists($job->outputPath)) {
                File::delete($job->outputPath);
            }
        }

        $this->assertFalse(isset($job->input));
        $this->assertFalse(TrainingProposalFeatureVector::exists());
    }
}


class GenerateAnnotationFeatureVectorsStub extends GenerateAnnotationFeatureVectors
{
    public $input;
    public $outputPath;
    public $output = [];

    protected function getAnnotations()
    {
        return $this->job->trainingProposals;
    }

    protected function python(array $input, string $outputPath)
    {
        $this->input = $input;
        $this->outputPath = $outputPath;
        $csv = implode("\n", array_map(fn ($row) => implode(',', $row), $this->output));
        File::put($outputPath, $csv);
    }

    protected function insertFeatureVectorModelChunk(array $chunk): void
    {
        TrainingProposalFeatureVector::insert($chunk);
    }
}
