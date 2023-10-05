<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgvector')
            ->create('maia_training_proposal_feature_vectors', function (Blueprint $table) {
                // Don't use increments() because it should throw an error if this is not
                // manually provided. It should be the ID of the model in the main DB.
                $table->unsignedInteger('id');
                $table->unsignedInteger('job_id')->index();
                $table->vector('vector', 384);

                $table->primary('id');
            });

        Schema::connection('pgvector')
            ->create('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
                // Don't use increments() because it should throw an error if this is not
                // manually provided. It should be the ID of the model in the main DB.
                $table->unsignedInteger('id');
                $table->unsignedInteger('job_id')->index();
                $table->vector('vector', 384);

                $table->primary('id');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgvector')
            ->dropIfExists('maia_training_proposal_feature_vectors');
        Schema::connection('pgvector')
            ->dropIfExists('maia_annotation_candidate_feature_vectors');
    }
};
