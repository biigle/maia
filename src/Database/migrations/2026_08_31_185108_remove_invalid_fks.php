<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->dropForeignKeyIfExists(
            'maia_training_proposals',
            'maia_training_proposals_shape_id_foreign',
        );

        $this->dropForeignKeyIfExists(
            'maia_annotation_candidates',
            'maia_annotation_candidates_shape_id_foreign',
        );
    }

    private function dropForeignKeyIfExists($table, $constraint)
    {
        $driverName = DB::getDriverName();
        if ($driverName !== 'pgsql') // TODO do we support others?
        {
            throw new RuntimeException("Unsupported DB driver '$driverName'. Only psql is supported");
        }

        DB::statement(sprintf(
            'alter table %s drop constraint if exists %s',
            $table, $constraint
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maia_training_proposals', function (Blueprint $t) {
             $t->foreign('shape_id')
                ->references('id')
                ->on('shapes')
                ->onDelete('restrict');
        });

        Schema::table('maia_annotation_candidates', function (Blueprint $t) {
             $t->foreign('shape_id')
                ->references('id')
                ->on('shapes')
                ->onDelete('restrict');
        });
    }
};
