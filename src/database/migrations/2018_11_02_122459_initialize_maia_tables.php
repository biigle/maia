<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitializeMaiaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maia_job_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
        });

        // Create all the possible job states.
        DB::table('maia_job_states')->insert([
            ['name' => 'novelty-detection'],
            ['name' => 'training-proposals'],
            ['name' => 'instance-segmentation'],
            ['name' => 'annotation-candidates'],
            ['name' => 'finished'],
        ]);

        /*
        | A MAIA job is created whenever a user wants to perform the MAIA method for a
        | volume. For each volume there can be only one MAIA job that is not finished.
        | All project editors, experts or admins can create or edit MAIA jobs.
        */
        Schema::create('maia_jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('volume_id')->unsigned()->index();
            $table->foreign('volume_id')
                  ->references('id')
                  ->on('volumes')
                  ->onDelete('cascade');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->timestamps();

            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')
                  ->references('id')
                  ->on('maia_job_states')
                  ->onDelete('restrict');

            // Parameters for novelty detection and training of Mask R-CNN
            $table->json('params')->nullable();
        });

        Schema::create('maia_annotation_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
        });

        // Create all the possible annotation types.
        DB::table('maia_annotation_types')->insert([
            ['name' => 'training-proposal'],
            ['name' => 'annotation-candidate'],
        ]);

        /*
        | In contrast to regular annotations, MAIA annotations are automatically created
        | by the method and should not be displayed in the regular annotation tool. Other
        | than that, they store exactly the same information than regular annotations.
        | There can be easily tens of thousands of MAIA annotations per MAIA job.
        */
        Schema::create('maia_annotations', function (Blueprint $table) {
            $table->increments('id');
            // JSON type cant have a default value so it must be nullable.
            $table->json('points')->nullable();
            $table->float('score');
            $table->boolean('selected');

            $table->integer('image_id')->unsigned()->index();
            $table->foreign('image_id')
                  ->references('id')
                  ->on('images')
                  ->onDelete('cascade');

            $table->integer('shape_id')->unsigned();
            $table->foreign('shape_id')
                  ->references('id')
                  ->on('shapes')
                  ->onDelete('restrict');

            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')
                  ->references('id')
                  ->on('maia_annotation_types')
                  ->onDelete('restrict');

            $table->integer('job_id')->unsigned();
            $table->foreign('job_id')
                  ->references('id')
                  ->on('maia_jobs')
                  ->onDelete('cascade');

            $table->index(['job_id', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maia_annotations');
        Schema::dropIfExists('maia_annotation_types');
        Schema::dropIfExists('maia_jobs');
        Schema::dropIfExists('maia_job_states');
    }
}
