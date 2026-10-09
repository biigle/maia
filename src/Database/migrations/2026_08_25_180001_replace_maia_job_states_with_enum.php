<?php

use Biigle\Enums\Shape;
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

    private $tablesWithShapeId = [
        'maia_annotation_candidates',
        'maia_training_proposals',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->replaceMaiaJobStatesTableWithEnum();
        $this->useEnumInShapeColumns();
    }

    private function replaceMaiaJobStatesTableWithEnum()
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

        EnumMigrationHelper::replaceStaticTableWithEnum($map, 'maia_job_states', $this->foreignKeys, validationMin: 1, validationMax: 6);
    }

    private function useEnumInShapeColumns()
    {
        $oldShapeIds = DB::table('shapes')->pluck('id', 'name');

        $shapeMap = [
            $oldShapeIds['Point']      => Shape::POINT->value,
            $oldShapeIds['LineString'] => Shape::LINE->value,
            $oldShapeIds['Polygon']    => Shape::POLYGON->value,
            $oldShapeIds['Circle']     => Shape::CIRCLE->value,
            $oldShapeIds['Rectangle']  => Shape::RECTANGLE->value,
            $oldShapeIds['Ellipse']    => Shape::ELLIPSE->value,
            $oldShapeIds['WholeFrame'] => Shape::WHOLE_FRAME->value,
        ];

        EnumMigrationHelper::assertCompleteMap($shapeMap, 'shapes');

        foreach ($this->tablesWithShapeId as $table)
        {
            EnumMigrationHelper::mapValues($shapeMap, $table, 'shape_id');
            Schema::table($table, function (Blueprint $t) {
                $t->renameColumn('shape_id', 'shape');
            });
            EnumMigrationHelper::addRangeValidationCheck($table, 'shape', validationMin: 1, validationMax: 7);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tablesWithShapeId as $table) {
            DB::statement(
                "ALTER TABLE $table DROP CONSTRAINT IF EXISTS {$table}_shape_check"
            );
            Schema::table($table, function (Blueprint $t) {
                $t->renameColumn('shape', 'shape_id');
            });
        }

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

        EnumMigrationHelper::createForeignKeys($this->foreignKeys, 'maia_job_states');
    }
};
