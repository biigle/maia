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
        Schema::create('maia_training_proposal_feature_vectors', function (Blueprint $table) {
                $table->unsignedBigInteger('id');
                $table->foreign('id')
                    ->references('id')
                    ->on('maia_training_proposals')
                    ->onDelete('cascade');

                $table->unsignedInteger('job_id')->index();
                $table->vector('vector', 384);

                $table->primary('id');
            });

        Schema::create('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
                $table->unsignedBigInteger('id');
                $table->foreign('id')
                    ->references('id')
                    ->on('maia_annotation_candidates')
                    ->onDelete('cascade');

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
        Schema::dropIfExists('maia_training_proposal_feature_vectors');
        Schema::dropIfExists('maia_annotation_candidate_feature_vectors');
    }
};
