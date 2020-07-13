<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatches;
use Biigle\Modules\Maia\Listeners\PrepareDeleteAnnotationPatches;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Queue;
use TestCase;

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
