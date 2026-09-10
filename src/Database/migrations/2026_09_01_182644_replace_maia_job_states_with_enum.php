<?php

use Biigle\Modules\Maia\MaiaJobState;
use Biigle\Support\EnumMigrationHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $foreignKeys = [
        ['maia_jobs', 'state_id']
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $oldIds = DB::table('maia_job_states')->pluck('id', 'name');
        $map = [
            $oldIds['novelty-detection']          => MaiaJobState::noveltyDetectionId(),
            $oldIds['failed-novelty-detection']   => MaiaJobState::failedNoveltyDetectionId(),
            $oldIds['training-proposals']         => MaiaJobState::trainingProposalsId(),
            $oldIds['annotation-candidates']      => MaiaJobState::annotationCandidatesId(),
            $oldIds['instance-segmentation']      => MaiaJobState::objectDetectionId(),
            $oldIds['failed-instance-segmentation'] => MaiaJobState::failedObjectDetectionId(),
        ];

        EnumMigrationHelper::replaceStaticTableWithEnum($map, 'maia_job_states', $this->foreignKeys);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('maia_job_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
        });

        DB::table('maia_job_states')->insert([
            ['id' => MaiaJobState::noveltyDetectionId(),        'name' => 'novelty-detection'],
            ['id' => MaiaJobState::failedNoveltyDetectionId(), 'name' => 'failed-novelty-detection'],
            ['id' => MaiaJobState::trainingProposalsId(),       'name' => 'training-proposals'],
            ['id' => MaiaJobState::annotationCandidatesId(),    'name' => 'annotation-candidates'],
            ['id' => MaiaJobState::objectDetectionId(),         'name' => 'instance-segmentation'],
            ['id' => MaiaJobState::failedObjectDetectionId(),   'name' => 'failed-instance-segmentation'],
        ]);

        EnumMigrationHelper::createForeignKeys($this->foreignKeys, 'maia_job_states');
    }
};
