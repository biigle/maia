<?php

namespace Biigle\Tests\Modules\Maia;

use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Event;
use ModelTestCase;

class MaiaJobTest extends ModelTestCase
{
    /**
     * The model class this class will test.
     */
    protected static $modelClass = MaiaJob::class;

    public function testAttributes()
    {
        $this->assertNotNull($this->model->volume);
        $this->assertNotNull($this->model->user);
        $this->assertNotNull($this->model->created_at);
        $this->assertNotNull($this->model->updated_at);
        $this->assertNotNull($this->model->state);
    }

    public function testTrainingProposals()
    {
        $a = TrainingProposalTest::create(['job_id' => $this->model->id]);
        $this->assertEquals($a->id, $this->model->trainingProposals()->first()->id);
    }

    public function testAnnotationCandidates()
    {
        $a = AnnotationCandidateTest::create(['job_id' => $this->model->id]);
        $this->assertEquals($a->id, $this->model->annotationCandidates()->first()->id);
    }

    public function testCastsParams()
    {
        $this->model->params = ['test'];
        $this->model->save();
        $this->assertEquals(['test'], $this->model->fresh()->params);
    }

    public function testCastsError()
    {
        $this->model->error = ['message' => 'test'];
        $this->model->save();
        $this->assertEquals(['message' => 'test'], $this->model->fresh()->error);
    }

    public function testIsRunning()
    {
        $this->assertTrue($this->model->isRunning());
        $this->model->state_id = State::objectDetectionId();
        $this->assertTrue($this->model->isRunning());
        $this->model->state_id = State::annotationCandidatesId();
        $this->assertFalse($this->model->isRunning());
    }

    public function testIsFailed()
    {
        $this->assertFalse($this->model->hasFailed());
        $this->model->state_id = State::failedNoveltyDetectionId();
        $this->assertTrue($this->model->hasFailed());
        $this->model->state_id = State::failedObjectDetectionId();
        $this->assertTrue($this->model->hasFailed());
    }

    public function testRequiresAction()
    {
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::trainingProposalsId();
        $this->assertTrue($this->model->requiresAction());
        $this->model->state_id = State::objectDetectionId();
        $this->assertFalse($this->model->requiresAction());
        $this->model->state_id = State::annotationCandidatesId();
        $this->assertFalse($this->model->requiresAction());
    }

    public function testDispatchesCreatedEvent()
    {
        Event::fake();
        static::create();
        Event::assertDispatched(MaiaJobCreated::class);
    }

    public function testDispatchesDeletingEvent()
    {
        Event::fake();
        $this->model->delete();
        Event::assertDispatched(MaiaJobDeleting::class);
    }

    public function testShouldUseNoveltyDetection()
    {
        $this->assertTrue($this->model->shouldUseNoveltyDetection());
        $this->model->params = ['training_data_method' => 'own_annotations'];
        $this->assertFalse($this->model->shouldUseNoveltyDetection());
        $this->model->params = ['training_data_method' => 'novelty_detection'];
        $this->assertTrue($this->model->shouldUseNoveltyDetection());
    }

    public function testShouldUseExistingAnnotations()
    {
        $this->assertFalse($this->model->shouldUseExistingAnnotations());
        $this->model->params = ['training_data_method' => 'novelty_detection'];
        $this->assertFalse($this->model->shouldUseExistingAnnotations());
        $this->model->params = ['training_data_method' => 'own_annotations'];
        $this->assertTrue($this->model->shouldUseExistingAnnotations());
    }

    public function testShouldUseKnowledgeTransfer()
    {
        $this->assertFalse($this->model->shouldUseKnowledgeTransfer());
        $this->model->params = ['training_data_method' => 'novelty_detection'];
        $this->assertFalse($this->model->shouldUseKnowledgeTransfer());
        $this->model->params = ['training_data_method' => 'knowledge_transfer'];
        $this->assertTrue($this->model->shouldUseKnowledgeTransfer());
        $this->model->params = ['training_data_method' => 'area_knowledge_transfer'];
        $this->assertTrue($this->model->shouldUseKnowledgeTransfer());
    }

    public function testConvertingCandidatesAttribute()
    {
        $this->assertFalse($this->model->convertingCandidates);
        $this->model->convertingCandidates = true;
        $this->assertTrue($this->model->convertingCandidates);
    }

    public function testShouldShowTrainingProposals()
    {
        $this->model->params = ['training_data_method' => 'novelty_detection'];
        $this->assertTrue($this->model->shouldShowTrainingProposals());
        $this->model->params = ['training_data_method' => 'knowledge_transfer'];
        $this->assertFalse($this->model->shouldShowTrainingProposals());
        $this->model->params = ['training_data_method' => 'own_annotations'];
        $this->assertFalse($this->model->shouldShowTrainingProposals());
        $this->model->params = [
            'training_data_method' => 'own_annotations',
            'oa_show_training_proposals' => true,
        ];
        $this->assertTrue($this->model->shouldShowTrainingProposals());
    }

    public function testDeleteFeatureVectorsCascade()
    {
        $tp = TrainingProposalFeatureVector::factory()->create([
            'job_id' => $this->model->id,
        ]);
        $ac = AnnotationCandidateFeatureVector::factory()->create([
            'job_id' => $this->model->id,
        ]);

        $this->model->delete();
        $this->assertNull($tp->fresh());
        $this->assertNull($ac->fresh());
    }
}
