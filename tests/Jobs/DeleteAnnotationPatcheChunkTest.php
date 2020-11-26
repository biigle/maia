<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatchChunk;
use Biigle\Tests\Modules\Maia\TrainingProposalTest;
use Storage;
use TestCase;

class DeleteAnnotationPatcheChunkTest extends TestCase
{
    public function testHandle()
    {
        Storage::fake('test');

        $tp = TrainingProposalTest::create();
        $tpPrefix = fragment_uuid_path($tp->image->uuid);
        $file = "{$tpPrefix}/{$tp->id}.jpg";
        Storage::disk('test')->put($file, 'content');

        (new DeleteAnnotationPatchChunk('test', [$file]))->handle();

        $this->assertFalse(Storage::disk('test')->exists("{$tpPrefix}/{$tp->id}.jpg"));
    }
}
