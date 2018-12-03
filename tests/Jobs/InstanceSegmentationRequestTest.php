<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use File;
use Queue;
use TestCase;
use Exception;
use ImageCache;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaAnnotationType;
use Biigle\Tests\Modules\Maia\MaiaAnnotationTest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationResponse;

class InstanceSegmentationRequestTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        ImageCache::fake();

        $params = [
            'available_bytes' => 8E+9,
            'max_workers' => 2,
        ];

        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $image2 = ImageTest::create(['volume_id' => $job->volume_id, 'filename' => 'a']);
        $trainingProposal = MaiaAnnotationTest::create([
            'job_id' => $job->id,
            'image_id' => $image->id,
            'type_id' => MaiaAnnotationType::trainingProposalId(),
            'points' => [10.5, 20.4, 30],
            'selected' => true,
        ]);
        // Not selected and should not be included.
        MaiaAnnotationTest::create([
            'job_id' => $job->id,
            'image_id' => $image->id,
            'type_id' => MaiaAnnotationType::trainingProposalId(),
            'selected' => false,
        ]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-instance-segmentation";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";
        $datasetOutputJsonPath = "{$tmpDir}/output-dataset.json";
        $trainingInputJsonPath = "{$tmpDir}/input-training.json";
        $trainingOutputJsonPath = "{$tmpDir}/output-training.json";
        $inferenceInputJsonPath = "{$tmpDir}/input-inference.json";

        $expectDatasetJson = [
            'available_bytes' => 8E+9,
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            'training_proposals' => [$image->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
        ];

        $expectTrainingJson = [
            'available_bytes' => 8E+9,
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            'output_path' => "{$tmpDir}/output-training.json",
            'coco_model_path' => config('maia.coco_model_path'),
        ];

        $expectInferenceJson = [
            'available_bytes' => 8E+9,
            'max_workers' => 2,
            'tmp_dir' => $tmpDir,
            // ...
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
            $this->assertContains("DatasetGenerator.py {$datasetInputJsonPath}", $request->commands[0]);

            $this->assertTrue(File::exists($trainingInputJsonPath));
            $inputJson = json_decode(File::get($trainingInputJsonPath), true);
            $this->assertEquals($expectTrainingJson, $inputJson);
            $this->assertContains("TrainingRunner.py {$trainingInputJsonPath} {$datasetOutputJsonPath}", $request->commands[1]);

            $this->markTestIncomplete();
            $this->assertTrue(File::exists($inferenceInputJsonPath));
            $inputJson = json_decode(File::get($inferenceInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            $this->assertArrayHasKey($image2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectInferenceJson, $inputJson);
            $this->assertContains("InferenceRunner.py {$inferenceInputJsonPath}", $request->commands[2]);


            Queue::assertPushed(InstanceSegmentationResponse::class);
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

    protected function maybeDownloadCocoModel()
    {
        // do nothing
    }

    protected function python($command, $log = 'log.txt')
    {
        array_push($this->commands, $command);

        if (str_contains($command, 'DatasetGenerator')) {
            File::put("{$this->tmpDir}/output-dataset.json", '{}');
        } elseif (str_contains($command, 'TrainingRunner')) {
            File::put("{$this->tmpDir}/output-training.json", '{}');
        } elseif (str_contains($command, 'InferenceRunner')) {
            $imageId = array_keys($this->images)[0];
            File::put("{$this->tmpDir}/{$imageId}.json", "[[30, 20, 10]]");
        }
    }

    protected function cleanup()
    {
        $this->cleanup = true;
    }
}
