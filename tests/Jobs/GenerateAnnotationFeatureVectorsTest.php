<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Image;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidateFeatureVectors;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\Shape;
use Exception;
use File;
use FileCache;
use TestCase;

class GenerateAnnotationFeatureVectorsTest extends TestCase
{
    public function testHandleTrainingProposals()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 5],
        ]);

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([5, 5, 15, 15], $box);

        $vectors = TrainingProposalFeatureVector::where('job_id', $j->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertSame($tp->id, $vectors[0]->id);
        $this->assertSame(range(0, 383), $vectors[0]->vector->toArray());
    }

    public function testHandleAnnotationCandidates()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $ac = AnnotationCandidate::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 5],
        ]);

        $job = new GenerateCandidateFeatureVectorsStub($j);
        $job->output = [[$ac->id, '"'.json_encode(range(0, 383)).'"']];
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
        $this->assertArrayHasKey($ac->id, $input[$filename]);
        $box = $input[$filename][$ac->id];
        $this->assertSame([5, 5, 15, 15], $box);

        $vectors = AnnotationCandidateFeatureVector::where('job_id', $j->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertSame($ac->id, $vectors[0]->id);
        $this->assertSame(range(0, 383), $vectors[0]->vector->toArray());
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([188, 188, 412, 412], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([10, 10, 20, 20], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([10, 10, 20, 20], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([10, 10, 20, 20], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([10, 10, 20, 20], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([0, 0, 30, 30], $box);
    }

    public function testHandleCoodinatesOutsideImageNegativeProblematic()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $image = Image::factory()->create([
            'attrs' => ['width' => 25, 'height' => 25],
            'volume_id' => $j->volume_id,
        ]);
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [10, 10, 15],
            'image_id' => $image->id,
        ]);

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        // Moving the box outside negative space makes it overflow in the positive space.
        $this->assertSame([0, 0, 25, 25], $box);
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

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([70, 70, 100, 100], $box);
    }

    public function testHandleCoodinatesOutsideImagePositiveProblematic()
    {
        FileCache::fake();
        $j = MaiaJob::factory()->create();
        $image = Image::factory()->create([
            'attrs' => ['width' => 25, 'height' => 25],
            'volume_id' => $j->volume_id,
        ]);
        $tp = TrainingProposal::factory()->create([
            'job_id' => $j->id,
            'shape_id' => Shape::circleId(),
            'points' => [15, 15, 15],
            'image_id' => $image->id,
        ]);

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        // Moving the box outside positive space makes it overflow in the negative space.
        $this->assertSame([0, 0, 25, 25], $box);
    }

    public function testHandleCoodinatesOutsideImageBoth()
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
            'points' => [50, 50, 55],
            'image_id' => $image->id,
        ]);

        $job = new GenerateProposalFeatureVectorsStub($j);
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
        $this->assertSame([0, 0, 100, 100], $box);
    }
}


class GenerateProposalFeatureVectorsStub extends GenerateTrainingProposalFeatureVectors
{
    public $input;
    public $outputPath;
    public $output = [];

    protected function python(string $inputPath, string $outputPath)
    {
        $this->input = json_decode(File::get($inputPath), true);
        $this->outputPath = $outputPath;
        $csv = implode("\n", array_map(fn ($row) => implode(',', $row), $this->output));
        File::put($outputPath, $csv);
    }
}

class GenerateCandidateFeatureVectorsStub extends GenerateAnnotationCandidateFeatureVectors
{
    public $input;
    public $outputPath;
    public $output = [];

    protected function python(string $inputPath, string $outputPath)
    {
        $this->input = json_decode(File::get($inputPath), true);
        $this->outputPath = $outputPath;
        $csv = implode("\n", array_map(fn ($row) => implode(',', $row), $this->output));
        File::put($outputPath, $csv);
    }
}
