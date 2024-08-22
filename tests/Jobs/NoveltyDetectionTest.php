<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\Jobs\NoveltyDetection;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Biigle\Shape;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use FileCache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use TestCase;

class NoveltyDetectionTest extends TestCase
{
    public function testHandle()
    {
        FileCache::fake();
        config([
            'maia.max_workers' => 2,
        ]);

        $params = [
            'nd_clusters' => 5,
            'nd_patch_size' => 39,
            'nd_threshold' => 99,
            'nd_latent_size' => 0.1,
            'nd_trainset_size' => 10000,
            'nd_epochs' => 100,
            'nd_stride' => 2,
            'nd_ignore_radius' => 5,
        ];
        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-novelty-detection";
        $inputJsonPath = "{$tmpDir}/input.json";
        $expectJson = array_merge($params, ['tmp_dir' => $tmpDir]);
        $expectJson['max_workers'] = 2; // Add here, because order is important

        try {
            $request = new NdJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));
            $this->assertTrue(File::exists($inputJsonPath));
            $inputJson = json_decode(File::get($inputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertSame($expectJson, $inputJson);
            $this->assertStringContainsString("DetectionRunner.py {$inputJsonPath}", $request->command);

            $this->assertSame(State::trainingProposalsId(), $job->fresh()->state_id);
            $annotations = $job->trainingProposals()->get();
            $this->assertSame(1, $annotations->count());
            $this->assertSame([100, 200, 20], $annotations[0]->points);
            $this->assertSame(0.9, $annotations[0]->score);
            $this->assertFalse($annotations[0]->selected);
            $this->assertSame($image->id, $annotations[0]->image_id);
            $this->assertSame(Shape::circleId(), $annotations[0]->shape_id);

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleLimit()
    {
        FileCache::fake();
        config(['maia.training_proposal_limit' => 1]);

        $params = [
            'nd_clusters' => 5,
            'nd_patch_size' => 39,
            'nd_threshold' => 99,
            'nd_latent_size' => 0.1,
            'nd_trainset_size' => 10000,
            'nd_epochs' => 100,
            'nd_stride' => 2,
            'nd_ignore_radius' => 5,
            'max_workers' => 2,
        ];
        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-novelty-detection";
        $inputJsonPath = "{$tmpDir}/input.json";

        try {
            $request = new NdJobStub($job);
            $request->annotations = "[[10,20,30,1],[10,20,30,123],[10,20,30,1]]";
            $request->handle();

            $annotations = $job->trainingProposals()->get();
            $this->assertSame(1, $annotations->count());
            $this->assertSame(123.0, $annotations[0]->score);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleRollbackOnError()
    {
        FileCache::fake();

        $params = [
            'nd_clusters' => 5,
            'nd_patch_size' => 39,
            'nd_threshold' => 99,
            'nd_latent_size' => 0.1,
            'nd_trainset_size' => 10000,
            'nd_epochs' => 100,
            'nd_stride' => 2,
            'nd_ignore_radius' => 5,
            'max_workers' => 2,
        ];

        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-novelty-detection";

        try {
            $request = new NdJobStub($job);
            $request->crash = true;
            $request->handle();
            $this->assertFalse(true);
        } catch (Exception $e) {
            //
        } finally {
            File::deleteDirectory($tmpDir);
        }

        $this->assertFalse($job->trainingProposals()->exists());
        $this->assertSame(State::noveltyDetectionId(), $job->fresh()->state_id);
    }

    public function testFailed()
    {
        config(['maia.debug_keep_files' => false]);

        $job = MaiaJobTest::create();
        $request = new NdJobStub($job);

        $request->failed(new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
        $this->assertTrue($request->cleanup);
    }

    public function testFailedDebug()
    {
        config(['maia.debug_keep_files' => true]);

        $job = MaiaJobTest::create();
        $request = new NdJobStub($job);

        $request->failed(new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
        $this->assertFalse($request->cleanup);
    }
}

class NdJobStub extends NoveltyDetection
{
    public $command = '';
    public $cleanup = false;
    public $crash = false;
    public $annotations = "[[100,200,20,0.9]]";

    protected function python($command, $log = 'log.txt')
    {
        $this->command = $command;
        $imageId = $this->job->volume->images()->first()->id;
        File::put("{$this->tmpDir}/{$imageId}.json", $this->annotations);
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
}
