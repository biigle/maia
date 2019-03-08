<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Queue;
use TestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatches;
use Biigle\Modules\Maia\Listeners\PrepareDeleteAnnotationPatches;

class PrepareDeleteAnnotationPatchesTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        $event = new MaiaJobDeleting(MaiaJobTest::create());
        (new PrepareDeleteAnnotationPatches)->handle($event);
        Queue::assertPushed(DeleteAnnotationPatches::class);
    }
}
