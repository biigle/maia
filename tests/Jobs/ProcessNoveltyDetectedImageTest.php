<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\ProcessNoveltyDetectedImage;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Mockery;
use Storage;
use TestCase;

class ProcessNoveltyDetectedImageTest extends TestCase
{
    public function testGeneratePatch()
    {
        $disk = Storage::fake('test');
        $image = $this->getImageMock();
        $proposal = TrainingProposal::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $job = new ProcessNoveltyDetectedImageStub($proposal->image, $proposal->job,
            targetDisk: 'test'
        );
        $job->mock = $image;

        $image->shouldReceive('crop')->once()->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');

        $job->handleFile($proposal->image, 'abc');
        $prefix = fragment_uuid_path($proposal->image->uuid);
        $content = $disk->get("{$prefix}/{$proposal->id}.jpg");
        $this->assertSame('abc123', $content);
    }

    public function testGenerateFeatureVector()
    {
        Storage::fake('test');
        $image = $this->getImageMock();
        $image->shouldReceive('crop')->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');
        $proposal = TrainingProposal::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $job = new ProcessNoveltyDetectedImageStub($proposal->image, $proposal->job,
            targetDisk: 'test'
        );
        $job->mock = $image;
        $job->handleFile($proposal->image, 'abc');

        $box = $job->capturedCropBoxes[0];
        $this->assertSame([190, 190, 20, 20], $box);

        $vectors = TrainingProposalFeatureVector::where('id', $proposal->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertSame($proposal->job_id, $vectors[0]->job_id);
        $this->assertSame(range(0, 383), $vectors[0]->vector->toArray());
    }

    public function testOnly()
    {
        $disk = Storage::fake('test');
        $image = $this->getImageMock();
        $proposal = TrainingProposal::factory()->create([
            'points' => [200, 200, 10],
        ]);
        $proposal2 = TrainingProposal::factory()->create([
            'points' => [200, 200, 10],
            'image_id' => $proposal->image_id,
            'job_id' => $proposal->job_id,
        ]);
        $job = new ProcessNoveltyDetectedImageStub($proposal->image, $proposal->job,
            targetDisk: 'test', only: [$proposal->id]
        );
        $job->mock = $image;

        $image->shouldReceive('crop')->once()->andReturn($image);
        $image->shouldReceive('writeToBuffer')->andReturn('abc123');

        $job->handleFile($proposal->image, 'abc');
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

class ProcessNoveltyDetectedImageStub extends ProcessNoveltyDetectedImage
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
