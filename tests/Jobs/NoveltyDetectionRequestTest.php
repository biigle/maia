<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use File;
use Queue;
use TestCase;
use Exception;
use ImageCache;
use Biigle\Tests\ImageTest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;

class NoveltyDetectionRequestTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        ImageCache::fake();

        $params = [
            'clusters' => 5,
            'patch_size' => 39,
            'threshold' => 99,
            'latent_size' => 0.1,
            'trainset_size' => 10000,
            'epochs' => 100,
            'available_bytes' => 8E+9,
            'max_workers' => 2,
        ];
        $job = MaiaJobTest::create(['params' => $params]);
        $image = ImageTest::create(['volume_id' => $job->volume_id]);
        $tmpDir = config('maia.tmp_dir')."/maia-{$job->id}";
        $inputJsonPath = "{$tmpDir}/input.json";

        $expectJson = array_merge($params, ['tmp_dir' => $tmpDir]);

        try {
            $request = new JobStub($job);
            $request->handle();

            $this->assertTrue(File::isDirectory($tmpDir));
            $this->assertTrue(File::exists($inputJsonPath));
            $inputJson = json_decode(File::get($inputJsonPath), true);
            $this->assertArrayHasKey('images', $inputJson);
            $this->assertArrayHasKey($image->id, $inputJson['images']);
            unset($inputJson['images']);
            $this->assertEquals($expectJson, $inputJson);

            $this->assertContains("DetectionRunner.py {$inputJsonPath}", $request->command);

            Queue::assertPushed(NoveltyDetectionResponse::class, function ($response) use ($job, $image) {
                return $response->jobId === $job->id
                    && $response->annotations === [$image->id => [[10, 20, 30, 123]]];
            });

            $this->assertTrue($request->cleanup);
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $request = new JobStub($job);
        $e = new Exception;

        Queue::fake();
        $request->failed($e);
        Queue::assertPushed(NoveltyDetectionFailure::class);
        $this->assertTrue($request->cleanup);
    }
}

class JobStub extends NoveltyDetectionRequest
{
    public $command = '';
    public $cleanup = false;

    protected function python($command)
    {
        $this->command = $command;
        $imageId = array_keys($this->images)[0];
        File::put("{$this->tmpDir}/{$imageId}.json", "[[10, 20, 30, 123]]");
    }

    protected function cleanup()
    {
        $this->cleanup = true;
    }
}
