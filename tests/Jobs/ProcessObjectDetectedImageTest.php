<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use Mockery;
use Storage;
use TestCase;

class ProcessObjectDetectedImageTest extends TestCase
{
    public function testGeneratePatch()
    {
        $disk = Storage::fake('test');
        $image = $this->getImageMock();
        $candidate = AnnotationCandidate::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $job = new ProcessObjectDetectedImageStub($candidate->image, $candidate->job,
            targetDisk: 'test'
        );
        $job->mock = $image;

        $image->shouldReceive('crop')->once()->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');

        $job->handleFile($candidate->image, 'abc');
        $prefix = fragment_uuid_path($candidate->image->uuid);
        $content = $disk->get("{$prefix}/{$candidate->id}.jpg");
        $this->assertSame('abc123', $content);
    }

    public function testGenerateFeatureVector()
    {
        Storage::fake('test');
        $image = $this->getImageMock();
        $image->shouldReceive('crop')->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');
        $candidate = AnnotationCandidate::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $job = new ProcessObjectDetectedImageStub($candidate->image, $candidate->job,
            targetDisk: 'test'
        );
        $job->mock = $image;
        $job->handleFile($candidate->image, 'abc');

        $box = $job->capturedCropBoxes[0];
        $this->assertSame([190, 190, 20, 20], $box);

        $vectors = AnnotationCandidateFeatureVector::where('id', $candidate->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertSame($candidate->job_id, $vectors[0]->job_id);
        $this->assertSame(range(0, 383), $vectors[0]->vector->toArray());
    }

    public function testOnly()
    {
        $disk = Storage::fake('test');
        $image = $this->getImageMock();
        $candidate = AnnotationCandidate::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $candidate2 = AnnotationCandidate::factory()->create([
            'points' => [200, 200, 10],
            'image_id' => $candidate->image_id,
            'job_id' => $candidate->job_id,
        ]);
        $job = new ProcessObjectDetectedImageStub($candidate->image, $candidate->job,
            targetDisk: 'test', only: [$candidate->id]
        );
        $job->mock = $image;

        $image->shouldReceive('crop')->once()->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');

        $job->handleFile($candidate->image, 'abc');
    }

    protected function getImageMock($times = 1)
    {
        $image = Mockery::mock();
        $image->width = 1000;
        $image->height = 750;
        $image->shouldReceive('resize')
            ->times($times)
            ->andReturn($image);

        return $image;
    }
}

class ProcessObjectDetectedImageStub extends ProcessObjectDetectedImage
{
    public $mock;
    public $featureVector;
    public $capturedCropBoxes = [];

    public function getVipsImage(string $path, array $options = [])
    {
        return $this->mock;
    }

    protected function getVipsImageForPyworker(string $path, array $options = [])
    {
        // Return the mock directly, skipping RGB conversion
        return $this->getVipsImage($path, $options);
    }

    protected function getCropBufferForPyworker($image, array $box): string
    {
        // Capture the bounding box for test assertions
        $this->capturedCropBoxes[] = $box;
        // Return a fake PNG buffer
        return 'fake-png-buffer';
    }

    protected function sendPyworkerRequest(string $buffer): array
    {
        return $this->featureVector ?: range(0, 383);
    }
}
