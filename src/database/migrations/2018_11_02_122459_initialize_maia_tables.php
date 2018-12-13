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
            ['name' => 'failed-novelty-detection'],
            ['name' => 'training-proposals'],
            ['name' => 'instance-segmentation'],
            ['name' => 'failed-instance-segmentation'],
            ['name' => 'annotation-candidates'],
        ]);

        /*
        | A MAIA job is created whenever a user wants to perform the MAIA method for a
        | volume. For each volume there can be only one MAIA job that is not finished
        | (i.e. in the annotation-candidates stage). All project editors, experts or
        | admins can create or edit MAIA jobs.
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

            // Stores parameters for novelty detection and training of Mask R-CNN,
            // as well as any error messages.
            $table->json('attrs')->nullable();
        });

        /*
        | In contrast to regular annotations, MAIA training proposals and annotation
        | candidates are automatically created by the method and should not be displayed
        | in the regular annotation tool. Other than that, they store almost the same
        | information than regular annotations. There can be easily tens of thousands of
        | MAIA annotations per MAIA job.
        */

        Schema::create('maia_training_proposals', function (Blueprint $table) {
            $table->increments('id');
            // JSON type cant have a default value so it must be nullable.
            $table->json('points')->nullable();
            // A score is null if the training proposal was created based on an existing
            // annotation. These proposals should be sorted before all others.
            $table->float('score')->nullable();
            $table->boolean('selected')->default(false);

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

            $table->integer('job_id')->unsigned();
            $table->foreign('job_id')
                  ->references('id')
                  ->on('maia_jobs')
                  ->onDelete('cascade');
        });

        Schema::create('maia_annotation_candidates', function (Blueprint $table) {
            $table->increments('id');
            // JSON type cant have a default value so it must be nullable.
            $table->json('points')->nullable();
            $table->float('score');

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

            $table->integer('job_id')->unsigned();
            $table->foreign('job_id')
                  ->references('id')
                  ->on('maia_jobs')
                  ->onDelete('cascade');

            $table->integer('label_id')->unsigned()->nullable();
            $table->foreign('label_id')
                  ->references('id')
                  ->on('labels')
                  ->onDelete('set null');

            // Once the annotation candidate has an assigned label and has beend
            // confirmed by the user, it is converted to a real annotation. This stores
            // a reference to the annotation that has been created from the annotation
            // candidate. If this is not null, the annotation candidate can no longer be
            // modified.
            $table->integer('annotation_id')->unsigned()->nullable();
            $table->foreign('annotation_id')
                  ->references('id')
                  ->on('annotations')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maia_annotation_candidates');
        Schema::dropIfExists('maia_training_proposals');
        Schema::dropIfExists('maia_jobs');
        Schema::dropIfExists('maia_job_states');
    }
}
