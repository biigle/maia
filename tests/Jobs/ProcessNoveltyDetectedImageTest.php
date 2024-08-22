<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\ProcessNoveltyDetectedImage;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use File;
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
        $job->output = [[$proposal->id, '"'.json_encode(range(0, 383)).'"']];
        $job->handleFile($proposal->image, 'abc');

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($proposal->id, $input[$filename]);
        $box = $input[$filename][$proposal->id];
        $this->assertSame([190, 190, 210, 210], $box);

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
    public $input;
    public $outputPath;
    public $output = [];

    public function getVipsImage(string $path, array $options = [])
    {
        return $this->mock;
    }

    protected function python(string $inputPath, string $outputPath)
    {
        $this->input = json_decode(File::get($inputPath), true);
        $this->outputPath = $outputPath;
        $csv = implode("\n", array_map(fn ($row) => implode(',', $row), $this->output));
        File::put($outputPath, $csv);
    }
}
