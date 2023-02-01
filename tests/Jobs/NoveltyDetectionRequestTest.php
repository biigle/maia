<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use File;
use FileCache;
use Queue;
use TestCase;

class NoveltyDetectionRequestTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
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
            'max_workers' => 2,
        ];
        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-novelty-detection";
        $inputJsonPath = "{$tmpDir}/input.json";
        $expectJson = array_merge($params, ['tmp_dir' => $tmpDir]);

        try {
            $request = new NdJobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));
            $this->assertTrue(File::exists($inputJsonPath));
            $inputJson = json_decode(File::get($inputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectJson, $inputJson);

            $this->assertStringContainsString("DetectionRunner.py {$inputJsonPath}", $request->command);

            Queue::assertPushed(NoveltyDetectionResponse::class, function ($response) use ($job, $image) {
                return $response->jobId === $job->id
                    && $response->annotations === [[$image->id, 10, 20, 30, 123]];
            });

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testHandleLimit()
    {
        Queue::fake();
        FileCache::fake();
        config([
            'maia.training_proposal_limit' => 2,
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
            'max_workers' => 2,
        ];
        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}-novelty-detection";
        $inputJsonPath = "{$tmpDir}/input.json";

        try {
            $request = new NdJobStub($job);
            $request->annotations = "[[10,20,30,1],[10,20,30,1],[10,20,30,123]]";
            $request->handle();

            Queue::assertPushed(NoveltyDetectionResponse::class, function ($response) use ($job, $image) {
                return $response->jobId === $job->id
                    && count($response->annotations) == 2
                    && in_array([$image->id, 10, 20, 30, 1], $response->annotations)
                    && in_array([$image->id, 10, 20, 30, 123], $response->annotations);
            });
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testFailed()
    {
        config(['maia.debug_keep_files' => false]);

        $job = MaiaJobTest::create();
        $request = new NdJobStub($job);

        Queue::fake();
        $request->failed(new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
        $this->assertTrue($request->cleanup);
    }

    public function testFailedDebug()
    {
        config(['maia.debug_keep_files' => true]);

        $job = MaiaJobTest::create();
        $request = new NdJobStub($job);

        Queue::fake();
        $request->failed(new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
        $this->assertFalse($request->cleanup);
    }
}

class NdJobStub extends NoveltyDetectionRequest
{
    public $command = '';
    public $cleanup = false;
    public $annotations = "[[10,20,30,123]]";

    protected function python($command, $log = 'log.txt')
    {
        $this->command = $command;
        $imageId = array_keys($this->images)[0];
        File::put("{$this->tmpDir}/{$imageId}.json", $this->annotations);
    }

    protected function cleanup()
    {
        $this->cleanup = true;
    }
}
