<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Jobs\ProcessObjectDetectedImage;
use File;
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
        $this->assertEquals('abc123', $content);
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
        $job->output = [[$candidate->id, '"'.json_encode(range(0, 383)).'"']];
        $job->handleFile($candidate->image, 'abc');

        $input = $job->input;
        $this->assertCount(1, $input);
        $filename = array_keys($input)[0];
        $this->assertArrayHasKey($candidate->id, $input[$filename]);
        $box = $input[$filename][$candidate->id];
        $this->assertEquals([190, 190, 210, 210], $box);

        $vectors = AnnotationCandidateFeatureVector::where('id', $candidate->id)->get();
        $this->assertCount(1, $vectors);
        $this->assertEquals($candidate->job_id, $vectors[0]->job_id);
        $this->assertEquals(range(0, 383), $vectors[0]->vector->toArray());
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
    public $input;
    public $outputPath;
    public $output = [];

    public function getVipsImage($path)
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
