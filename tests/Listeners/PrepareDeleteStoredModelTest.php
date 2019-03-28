<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Queue;
use TestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\Jobs\DeleteStoredModel;
use Biigle\Modules\Maia\Listeners\PrepareDeleteStoredModel;

class PrepareDeleteStoredModelTest extends TestCase
{
    public function testHandle()
    {
        Queue::fake();
        $event = new MaiaJobDeleting(MaiaJobTest::create());
        (new PrepareDeleteStoredModel)->handle($event);
        Queue::assertPushed(DeleteStoredModel::class);

    }
}
