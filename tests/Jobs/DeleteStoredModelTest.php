<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Storage;
use TestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Jobs\DeleteStoredModel;

class DeleteStoredModelTest extends TestCase
{
    public function testHandle()
    {
        config(['maia.model_storage_disk' => 'test']);
        $job = MaiaJobTest::create();
        $disk = Storage::disk('test');
        $disk->put($job->id, 'model');
        (new DeleteStoredModel($job))->handle();
        $this->assertFalse($disk->exists($job->id));
    }
}
