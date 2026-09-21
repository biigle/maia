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
            $oldIds['novelty-detection']          => MaiaJobState::NOVELTY_DETECTION->value,
            $oldIds['failed-novelty-detection']   => MaiaJobState::FAILED_NOVELTY_DETECTION->value,
            $oldIds['training-proposals']         => MaiaJobState::TRAINING_PROPOSALS->value,
            $oldIds['annotation-candidates']      => MaiaJobState::ANNOTATION_CANDIDATES->value,
            $oldIds['instance-segmentation']      => MaiaJobState::OBJECT_DETECTION->value,
            $oldIds['failed-instance-segmentation'] => MaiaJobState::FAILED_OBJECT_DETECTION->value,
        ];

        EnumMigrationHelper::replaceStaticTableWithEnum($map, 'maia_job_states', $this->foreignKeys, true);
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
            ['id' => MaiaJobState::NOVELTY_DETECTION->value,        'name' => 'novelty-detection'],
            ['id' => MaiaJobState::FAILED_NOVELTY_DETECTION->value, 'name' => 'failed-novelty-detection'],
            ['id' => MaiaJobState::TRAINING_PROPOSALS->value,       'name' => 'training-proposals'],
            ['id' => MaiaJobState::ANNOTATION_CANDIDATES->value,    'name' => 'annotation-candidates'],
            ['id' => MaiaJobState::OBJECT_DETECTION->value,         'name' => 'instance-segmentation'],
            ['id' => MaiaJobState::FAILED_OBJECT_DETECTION->value,   'name' => 'failed-instance-segmentation'],
        ]);

        EnumMigrationHelper::createForeignKeys($this->foreignKeys, 'maia_job_states', true);
    }
};
