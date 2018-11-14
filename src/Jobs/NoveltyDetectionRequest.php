<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Exception;
use Biigle\Modules\Maia\MaiaJob;

/**
 * This job is executed on a machine with GPU access.
 */
class NoveltyDetectionRequest extends JobRequest
{
    /**
     * Execute the job
     */
    public function handle()
    {
        // TODO Run actual novelty detection here.
        if ($this->images->isNotEmpty()) {
            $id = $this->images->keys()->first();
            $this->dispatchResponse([$id => [
                [200, 200, 100, 0.5],
                [400, 400, 100, 0.7],
                [200, 400, 100, 0.1],
                [400, 200, 100, 1.0],
            ]]);
        } else {
            $this->dispatchResponse([]);
        }
    }

    /**
     * Dispatch the job to store the novelty detection results.
     *
     * @param array $annotations
     */
    protected function dispatchResponse($annotations)
    {
        $this->dispatch(new NoveltyDetectionResponse($this->jobId, $annotations));
    }

    /**
     * {@inheritdoc}
     */
    protected function cleanup()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        $this->dispatch(new NoveltyDetectionFailure($this->jobId, $e));
    }
}
