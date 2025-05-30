<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidateFeatureVectors;
use Biigle\Modules\Maia\Jobs\ObjectDetection;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Jobs\ObjectDetectionResponse;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionComplete;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Exception;
use FileCache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Str;
use TestCase;
use Biigle\Image;

class ObjectDetectionTest extends TestCase
{
    public function testHandle()
    {
        FileCache::fake();
        config([
            'maia.mmdet_train_batch_size' => 12,
            'maia.max_workers' => 2,
        ]);

        $params = [
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
        $tmpDir = "/tmp/maia-{$job->id}-object-detection";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";
        $datasetOutputJsonPath = "{$tmpDir}/output-dataset.json";
        $trainingInputJsonPath = "{$tmpDir}/input-training.json";
        $trainingOutputJsonPath = "{$tmpDir}/output-training.json";
        $inferenceInputJsonPath = "{$tmpDir}/input-inference.json";

        $expectDatasetJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
            'training_proposals' => [$image->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
        ];

        $expectTrainingJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
            'output_path' => "{$tmpDir}/output-training.json",
            'base_config' => config('maia.mmdet_base_config'),
            'batch_size' => 12,
            'backbone_model_path' => config('maia.backbone_model_path'),
            'model_path' => config('maia.model_path'),
        ];

        $expectInferenceJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
        ];

        try {
            $request = new OdJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));

            $this->assertTrue(File::exists($datasetInputJsonPath));
            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            $this->assertArrayNotHasKey($image2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertSame($expectDatasetJson, $inputJson);
            $this->assertStringContainsString("DatasetGenerator.py {$datasetInputJsonPath}", $request->commands[0]);

            $this->assertTrue(File::exists($trainingInputJsonPath));
            $inputJson = json_decode(File::get($trainingInputJsonPath), true);
            $this->assertSame($expectTrainingJson, $inputJson);
            $this->assertStringContainsString("TrainingRunner.py {$trainingInputJsonPath} {$datasetOutputJsonPath}", $request->commands[1]);

            $this->assertTrue(File::exists($inferenceInputJsonPath));
            $inputJson = json_decode(File::get($inferenceInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            $this->assertArrayHasKey($image2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertSame($expectInferenceJson, $inputJson);
            $this->assertStringContainsString("InferenceRunner.py {$inferenceInputJsonPath} {$datasetOutputJsonPath} {$trainingOutputJsonPath}", $request->commands[2]);

            $this->assertSame(State::annotationCandidatesId(), $job->fresh()->state_id);

            $annotations = $job->annotationCandidates()->get();
            // One annotation for each image.
            $this->assertSame(2, $annotations->count());
            $this->assertSame([10, 20, 30], $annotations[0]->points);
            $this->assertSame(123.0, $annotations[0]->score);
            $this->assertNull($annotations[0]->label_id);
            $this->assertNull($annotations[0]->annotation_id);
            $this->assertSame($image->id, $annotations[0]->image_id);
            $this->assertSame(Shape::circleId(), $annotations[0]->shape_id);

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testDeletedImage()
    {
        FileCache::fake();

        $job = MaiaJobTest::create();
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $image2 = ImageTest::create(['volume_id' => $job->volume_id, 'filename' => 'a']);

        config(['maia.tmp_dir' => '/tmp']);
        $tmpDir = "/tmp/maia-{$job->id}-object-detection";

        try {
            $request = new OdJobStub($job);
            $request->deleteImage = true;
            $request->handle();

            $annotations = $job->annotationCandidates()->get();
            // One image was deleted, only one image and annotation remains.
            $this->assertSame(1, $annotations->count());
            $this->assertSame($image2->id, $annotations[0]->image_id);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleKnowledgeTransfer()
    {
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
        $tmpDir = "/tmp/maia-{$job->id}-object-detection";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";
        $datasetOutputJsonPath = "{$tmpDir}/output-dataset.json";
        $trainingInputJsonPath = "{$tmpDir}/input-training.json";
        $trainingOutputJsonPath = "{$tmpDir}/output-training.json";
        $inferenceInputJsonPath = "{$tmpDir}/input-inference.json";

        $expectDatasetJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
            'training_proposals' => [$otherImage->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
        ];

        $expectTrainingJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
            'output_path' => "{$tmpDir}/output-training.json",
            'base_config' => config('maia.mmdet_base_config'),
            'batch_size' => 12,
            'backbone_model_path' => config('maia.backbone_model_path'),
            'model_path' => config('maia.model_path'),
        ];

        $expectInferenceJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
        ];

        try {
            $request = new OdJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));

            $this->assertTrue(File::exists($datasetInputJsonPath));
            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($otherImage->id, $inputJson['images']);
            $this->assertArrayNotHasKey($otherImage2->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertSame($expectDatasetJson, $inputJson);
            $this->assertStringContainsString("DatasetGenerator.py {$datasetInputJsonPath}", $request->commands[0]);

            $this->assertTrue(File::exists($trainingInputJsonPath));
            $inputJson = json_decode(File::get($trainingInputJsonPath), true);
            $this->assertSame($expectTrainingJson, $inputJson);
            $this->assertStringContainsString("TrainingRunner.py {$trainingInputJsonPath} {$datasetOutputJsonPath}", $request->commands[1]);

            $this->assertTrue(File::exists($inferenceInputJsonPath));
            $inputJson = json_decode(File::get($inferenceInputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($ownImage->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertSame($expectInferenceJson, $inputJson);
            $this->assertStringContainsString("InferenceRunner.py {$inferenceInputJsonPath} {$datasetOutputJsonPath} {$trainingOutputJsonPath}", $request->commands[2]);

            $annotations = $job->annotationCandidates()->get();
            $this->assertSame(1, $annotations->count());
            $this->assertSame([10, 20, 30], $annotations[0]->points);
            $this->assertSame(123.0, $annotations[0]->score);
            $this->assertSame($ownImage->id, $annotations[0]->image_id);

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleAreaKnowledgeTransfer()
    {
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
        $tmpDir = "/tmp/maia-{$job->id}-object-detection";
        $datasetInputJsonPath = "{$tmpDir}/input-dataset.json";

        $expectDatasetJson = [
            'tmp_dir' => $tmpDir,
            'max_workers' => 2,
            'training_proposals' => [$otherImage->id => [[11, 20, 30]]],
            'output_path' => "{$tmpDir}/output-dataset.json",
            'kt_scale_factors' => [
                $otherImage->id => 0.25,
                $otherImage2->id => 0.25,
            ],
        ];

        try {
            $request = new OdJobStub($job);
            $request->handle();

            $inputJson = json_decode(File::get($datasetInputJsonPath), true);
            unset($inputJson['images']);
            $this->assertSame($expectDatasetJson, $inputJson);

            $annotations = $job->annotationCandidates()->get();
            $this->assertSame(1, $annotations->count());
            $this->assertSame([10, 20, 30], $annotations[0]->points);
            $this->assertSame(123.0, $annotations[0]->score);
            $this->assertSame($ownImage->id, $annotations[0]->image_id);

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleRollbackOnError()
    {
        FileCache::fake();
        config([
            'maia.mmdet_train_batch_size' => 12,
            'maia.max_workers' => 2,
        ]);

        $params = [
            'batch_size' => 12,
            'max_workers' => 2,
        ];

        $job = MaiaJobTest::create([
            'params' => $params,
            'state_id' => State::objectDetectionId(),
        ]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $trainingProposal = TrainingProposalTest::create([
            'job_id' => $job->id,
            'image_id' => $image->id,
            'points' => [10.5, 20.4, 30],
            'selected' => true,
        ]);
        config(['maia.tmp_dir' => '/tmp']);
        $tmpDir = "/tmp/maia-{$job->id}-object-detection";

        try {
            $request = new OdJobStub($job);
            $request->crash = true;
            $request->handle();

        } catch (Exception $e) {
            //
        } finally {
            File::deleteDirectory($tmpDir);
        }

        $this->assertFalse($job->annotationCandidates()->exists());
        $this->assertSame(State::objectDetectionId(), $job->fresh()->state_id);
    }

    public function testFailed()
    {
        config(['maia.debug_keep_files' => false]);

        $job = MaiaJobTest::create();
        $request = new OdJobStub($job);

        $request->failed(new Exception);
        Queue::assertPushed(ObjectDetectionFailure::class);
        $this->assertTrue($request->cleanup);
    }

    public function testFailedDebug()
    {
        config(['maia.debug_keep_files' => true]);

        $job = MaiaJobTest::create();
        $request = new OdJobStub($job);

        $request->failed(new Exception);
        Queue::assertPushed(ObjectDetectionFailure::class);
        $this->assertFalse($request->cleanup);
    }
}

class OdJobStub extends ObjectDetection
{
    public $commands = [];
    public $cleanup = false;
    public $crash = false;
    public $deleteImage = false;

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
            $images = $this->job->volume->images()->pluck('filename', 'id');
            foreach ($images as $id => $image) {
                File::put("{$this->tmpDir}/{$id}.json", "[[10, 20, 30, 123]]");
            }
        }
    }

    protected function cleanup()
    {
        $this->cleanup = true;
    }

    protected function updateJobState()
    {
        if ($this->crash) {
            throw new Exception('Something went wrong!');
        }

        return parent::updateJobState();
    }

    public function createMaiaAnnotations($annotations)
    {
        // Simulate image removal after saving its annotations
        if ($this->deleteImage && !empty($annotations)) {
            Image::where('id', '=', $annotations[0][0])->delete();
        }
        parent::createMaiaAnnotations($annotations);
    }
}
