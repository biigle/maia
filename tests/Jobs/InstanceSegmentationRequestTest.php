<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationResponse;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Exception;
use File;
use FileCache;
use Queue;
use Str;
use TestCase;

class InstanceSegmentationRequestTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        FileCache::fake();
        config([
            'maia.mmdet_train_batch_size' => 12,
            'maia.max_workers' => 2,
        ]);

        $params = [
            'is_train_scheme' => [
                ['layers' => 'all', 'epochs' => 10, 'learning_rate' => 0.001],
            ],
            'batch_size' => 12,
            'max_workers' => 2,
        ];

        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $image2 = ImageTest::create(['volume_id' => $job->volume_id, 'filename' => 'a']);
        $trainingProposal = TrainingProposalTest::create([
            'job_id' => $job->id,
            'image_id' => $image->id,
            'points' => [10.5, 20.4, 30],
            'selected' => true,
        ]);
        // Not selected and should not be included.
        TrainingProposalTest::create([
            'job_id' => $job->id,
            'image_id' => $image->id,
            'selected' => false,
        ]);
        config(['maia.tmp_dir' => '/tmp']);
        $tmpDir = "/tmp/maia-{$job->id}-instance-segmentation";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";
        $datasetOutputJsonPath = "{$tmpDir}/output-dataset.json";
        $trainingInputJsonPath = "{$tmpDir}/input-training.json";
        $trainingOutputJsonPath = "{$tmpDir}/output-training.json";
        $inferenceInputJsonPath = "{$tmpDir}/input-inference.json";

        $expectDatasetJson = [
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            'training_proposals' => [$image->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
        ];

        $expectTrainingJson = [
            'is_train_scheme' => [
                ['layers' => 'all', 'epochs' => 10, 'learning_rate' => 0.001],
            ],
            'max_workers' => 2,
            'batch_size' => 12,
            'tmp_dir' => $tmpDir,
            'output_path' => "{$tmpDir}/output-training.json",
            'base_config' => config('maia.mmdet_base_config'),
            'backbone_model_path' => config('maia.backbone_model_path'),
            'model_path' => config('maia.model_path'),
        ];

        $expectInferenceJson = [
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
        ];

        try {
            $request = new IsJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));

            $this->assertTrue(File::exists($datasetInputJsonPath));
            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            $this->assertArrayNotHasKey($image2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectDatasetJson, $inputJson);
            $this->assertStringContainsString("DatasetGenerator.py {$datasetInputJsonPath}", $request->commands[0]);

            $this->assertTrue(File::exists($trainingInputJsonPath));
            $inputJson = json_decode(File::get($trainingInputJsonPath), true);
            $this->assertEquals($expectTrainingJson, $inputJson);
            $this->assertStringContainsString("TrainingRunner.py {$trainingInputJsonPath} {$datasetOutputJsonPath}", $request->commands[1]);

            $this->assertTrue(File::exists($inferenceInputJsonPath));
            $inputJson = json_decode(File::get($inferenceInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            $this->assertArrayHasKey($image2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectInferenceJson, $inputJson);
            $this->assertStringContainsString("InferenceRunner.py {$inferenceInputJsonPath} {$datasetOutputJsonPath} {$trainingOutputJsonPath}", $request->commands[2]);

            Queue::assertPushed(InstanceSegmentationResponse::class, function ($response) use ($job, $image) {
                return $response->jobId === $job->id
                    && in_array([$image->id, 10, 20, 30, 123], $response->annotations);
            });

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleKnowledgeTransfer()
    {
        Queue::fake();
        FileCache::fake();
        config([
            'maia.mmdet_train_batch_size' => 12,
            'maia.max_workers' => 2,
        ]);

        $otherImage = ImageTest::create();
        $otherImage2 = ImageTest::create([
            'volume_id' => $otherImage->volume_id,
            'filename' => 'a',
        ]);

        $params = [
            'training_data_method' => 'knowledge_transfer',
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
            'kt_volume_id' => $otherImage->volume_id,
            'is_train_scheme' => [
                ['layers' => 'all', 'epochs' => 10, 'learning_rate' => 0.001],
            ],
            'batch_size' => 12,
            'max_workers' => 2,
        ];

        $ownImage = ImageTest::create();

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => $params,
        ]);
        $trainingProposal = TrainingProposalTest::create([
            'job_id' => $job->id,
            'image_id' => $otherImage->id,
            'points' => [10.5, 20.4, 30],
            'selected' => true,
        ]);
        config(['maia.tmp_dir' => '/tmp']);
        $tmpDir = "/tmp/maia-{$job->id}-instance-segmentation";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";
        $datasetOutputJsonPath = "{$tmpDir}/output-dataset.json";
        $trainingInputJsonPath = "{$tmpDir}/input-training.json";
        $trainingOutputJsonPath = "{$tmpDir}/output-training.json";
        $inferenceInputJsonPath = "{$tmpDir}/input-inference.json";

        $expectDatasetJson = [
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            'training_proposals' => [$otherImage->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
        ];

        $expectTrainingJson = [
            'is_train_scheme' => [
                ['layers' => 'all', 'epochs' => 10, 'learning_rate' => 0.001],
            ],
            'max_workers' => 2,
            'batch_size' => 12,
            'tmp_dir' => $tmpDir,
            'output_path' => "{$tmpDir}/output-training.json",
            'base_config' => config('maia.mmdet_base_config'),
            'backbone_model_path' => config('maia.backbone_model_path'),
            'model_path' => config('maia.model_path'),
        ];

        $expectInferenceJson = [
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
        ];

        try {
            $request = new IsJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));

            $this->assertTrue(File::exists($datasetInputJsonPath));
            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($otherImage->id, $inputJson['images']);
            $this->assertArrayNotHasKey($otherImage2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectDatasetJson, $inputJson);
            $this->assertStringContainsString("DatasetGenerator.py {$datasetInputJsonPath}", $request->commands[0]);

            $this->assertTrue(File::exists($trainingInputJsonPath));
            $inputJson = json_decode(File::get($trainingInputJsonPath), true);
            $this->assertEquals($expectTrainingJson, $inputJson);
            $this->assertStringContainsString("TrainingRunner.py {$trainingInputJsonPath} {$datasetOutputJsonPath}", $request->commands[1]);

            $this->assertTrue(File::exists($inferenceInputJsonPath));
            $inputJson = json_decode(File::get($inferenceInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($ownImage->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectInferenceJson, $inputJson);
            $this->assertStringContainsString("InferenceRunner.py {$inferenceInputJsonPath} {$datasetOutputJsonPath} {$trainingOutputJsonPath}", $request->commands[2]);

            Queue::assertPushed(InstanceSegmentationResponse::class, function ($response) use ($job, $ownImage) {
                return $response->jobId === $job->id
                    && in_array([$ownImage->id, 10, 20, 30, 123], $response->annotations);
            });

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleAreaKnowledgeTransfer()
    {
        Queue::fake();
        FileCache::fake();
        config([
            'maia.mmdet_train_batch_size' => 12,
            'maia.max_workers' => 2,
        ]);

        $otherImage = ImageTest::create();
        $otherImage2 = ImageTest::create([
            'volume_id' => $otherImage->volume_id,
            'filename' => 'a',
        ]);

        $params = [
            'training_data_method' => 'area_knowledge_transfer',
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
            'kt_volume_id' => $otherImage->volume_id,
            'is_train_scheme' => [
                ['layers' => 'all', 'epochs' => 10, 'learning_rate' => 0.001],
            ],
            'batch_size' => 12,
            'max_workers' => 2,
        ];

        $ownImage = ImageTest::create();

        $job = MaiaJobTest::create([
            'volume_id' => $ownImage->volume_id,
            'params' => $params,
        ]);
        $trainingProposal = TrainingProposalTest::create([
            'job_id' => $job->id,
            'image_id' => $otherImage->id,
            'points' => [10.5, 20.4, 30],
            'selected' => true,
        ]);
        config(['maia.tmp_dir' => '/tmp']);
        $tmpDir = "/tmp/maia-{$job->id}-instance-segmentation";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";

        $expectDatasetJson = [
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            'training_proposals' => [$otherImage->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
        ];

        try {
            $request = new IsJobStub($job);
            $request->handle();

            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            unset($inputJson['images']);
            $this->assertEquals($expectDatasetJson, $inputJson);

            Queue::assertPushed(InstanceSegmentationResponse::class, function ($response) use ($job, $ownImage) {
                return $response->jobId === $job->id
                    && in_array([$ownImage->id, 10, 20, 30, 123], $response->annotations);
            });

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $request = new IsJobStub($job);

        Queue::fake();
        $request->failed(new Exception);
        Queue::assertPushed(InstanceSegmentationFailure::class);
    }
}

class IsJobStub extends InstanceSegmentationRequest
{
    public $commands = [];
    public $cleanup = false;

    protected function maybeDownloadWeights($from, $to)
    {
        // do nothing
    }

    protected function python($command, $log = 'log.txt')
    {
        array_push($this->commands, $command);

        if (Str::contains($command, 'DatasetGenerator')) {
            File::put("{$this->tmpDir}/output-dataset.json", '{}');
        } elseif (Str::contains($command, 'TrainingRunner')) {
            File::put("{$this->tmpDir}/output-training.json", '{}');
        } elseif (Str::contains($command, 'InferenceRunner')) {
            foreach ($this->images as $id => $image) {
                File::put("{$this->tmpDir}/{$id}.json", "[[10, 20, 30, 123]]");
            }
        }
    }

    protected function cleanup()
    {
        $this->cleanup = true;
    }
}
