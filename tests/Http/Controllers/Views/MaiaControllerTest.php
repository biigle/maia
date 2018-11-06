<?php

namespace Biigle\Tests\Modules\Maia\Http\Controllers\Views;

use ApiTestCase;

class MaiaControllerTest extends ApiTestCase
{
    public function testShow()
    {
        $id = $this->volume()->id;

        // Not logged in.
        $this->get("volumes/{$id}/maia")->assertStatus(302);

        // Doesn't belong to project.
        $this->beUser();
        $this->get("volumes/{$id}/maia")->assertStatus(403);

        // Not allowed to perform MAIA jobs.
        $this->beGuest();
        $this->get("volumes/{$id}/maia")->assertStatus(403);

        $this->beEditor();
        $this->get("volumes/{$id}/maia")->assertStatus(200);
    }
}
