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
        Schema::table('maia_jobs', fn (Blueprint $t) => $t->dropForeign(['state_id']));
        Schema::dropIfExists('maia_job_states');
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
            ['name' => 'novelty-detection'],
            ['name' => 'failed-novelty-detection'],
            ['name' => 'training-proposals'],
            ['name' => 'annotation-candidates'],
            ['name' => 'instance-segmentation'],
            ['name' => 'failed-instance-segmentation'],
        ]);

        Schema::table('maia_jobs', function (Blueprint $t) {
            $t->foreign('state_id')
                ->references('id')
                ->on('maia_job_states')
                ->onDelete('restrict');
        });
    }
};
