<?php

namespace Biigle\Modules\Maia\Http\Controllers\Api;

use Queue;
use Biigle\Volume;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Http\Controllers\Api\Controller;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Http\Requests\StoreMaiaJob;
use Biigle\Modules\Maia\Http\Requests\UpdateMaiaJob;
use Biigle\Modules\Maia\Http\Requests\DestroyMaiaJob;

class MaiaJobController extends Controller
{
    /**
     * Creates a new MAIA job for the specified volume.
     *
     * @api {post} volumes/:id/maia-jobs Create a new MAIA job
     * @apiGroup MaiaJobs
     * @apiName StoreMaiaJob
     * @apiPermission projectEditor
     * @apiDescription New MAIA jobs cannot be created for volumes with very large (tiled) images.
     *
     * @apiParam {Number} id The volume ID.
     *
     * @apiParam (Required attributes) {Number} clusters Number of different kinds of images to expect. Images are of the same kind if they have similar lighting conditions or show similar patterns (e.g. sea floor, habitat types). Increase this number if you expect many different kinds of images. Lower the number to 1 if you have very few images and/or the content is largely uniform.
     * @apiParam (Required attributes) {number} patch_size Size in pixels of the image patches used determine the training proposals. Increase the size if the images contain larger objects of interest, decrease the size if the objects are smaller. Larger patch sizes take longer to compute. Must be an odd number.
     * @apiParam (Required attributes) {number} threshold Percentile of pixel saliency values used to determine the saliency threshold. Lower this value to get more training proposals. The default value should be fine for most cases.
     * @apiParam (Required attributes) {number} latent_size Learning capability used to determine training proposals. Increase this number to ignore more complex objects and patterns.
     * @apiParam (Required attributes) {number} trainset_size Number of training image patches used to determine training proposals. You can increase this number for a large volume but it will take longer to compute.
     * @apiParam (Required attributes) {number} epochs Time spent on training when determining the training proposals.
     * @apiParam (Required attributes) {number} stride A higher stride increases the speed of the novelty detection but reduces the sensitivity to small regions or objects.
     * @apiParam (Required attributes) {number} ignore_radius Ignore training proposals or annotation candidates which have a radius smaller or equal than this value in pixels.
     *
     * @param StoreMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMaiaJob $request)
    {
        $job = new MaiaJob;
        $job->volume_id = $request->volume->id;
        $job->user_id = $request->user()->id;
        $job->state_id = State::noveltyDetectionId();
        $job->params = $request->only([
            'clusters',
            'patch_size',
            'threshold',
            'latent_size',
            'trainset_size',
            'epochs',
            'stride',
            'ignore_radius',
        ]);
        $job->save();

        if ($this->isAutomatedRequest()) {
            return $job;
        }

        return $this->fuzzyRedirect('maia', $job->id);
    }

    /**
     * Continue a MAIA job from training proposal selection and refinement to instance segmentation.
     *
     * @api {put} maia-jobs/:id
     * @apiGroup MaiaJobs
     * @apiName UpdateMaiaJob
     * @apiPermission projectEditor
     * @apiDescription A job can only be continued if it is in training proposal selection and refinement state, and if it has selected training proposals.
     *
     * @apiParam {Number} id The job ID.
     *
     * @param UpdateMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaiaJob $request)
    {
        $request->job->state_id = State::instanceSegmentationId();
        $request->job->save();
        event(new MaiaJobContinued($request->job));

        if (!$this->isAutomatedRequest()) {
            return $this->fuzzyRedirect('maia', $request->job->id);
        }
    }

    /**
     * Delete a MAIA job.
     *
     * @api {delete} maia-jobs/:id
     * @apiGroup MaiaJobs
     * @apiName DestroyMaiaJob
     * @apiPermission projectEditor
     *
     * @apiParam {Number} id The job ID.
     *
     * @param DestroyMaiaJob $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyMaiaJob $request)
    {
        $volumeId = $request->job->volume_id;
        $request->job->delete();

        if (!$this->isAutomatedRequest()) {
            return $this->fuzzyRedirect('volumes-maia', $volumeId)
                ->with('message', 'Job deleted')
                ->with('messageType', 'success');
        }
    }
}
