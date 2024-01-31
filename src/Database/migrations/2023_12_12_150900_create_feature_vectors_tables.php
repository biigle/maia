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
                // This constraint turned out to be very problematic. When a MAIA job
                // is deleted, the deletion will cascade to training proposals and then
                // to their feature vectors (here). Since this column has no index, the
                // deletion of feature vectors is *very* inefficient on a large table
                // (which is expected with MAIA). The deletion takes hours to days!
                //
                // The foreign key constraint is dropped in a later migration because
                // individual training proposals cannot be deleted. Deletion of feature
                // vectors will then cascade from the deletion of the job, which is
                // indexed below.
                $table->foreign('id')
                    ->references('id')
                    ->on('maia_training_proposals')
                    ->onDelete('cascade');

                $table->unsignedInteger('job_id')->index();
                $table->foreign('job_id')
                    ->references('id')
                    ->on('maia_jobs')
                    ->onDelete('cascade');

                $table->vector('vector', 384);
            });

        Schema::create('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
                $table->unsignedBigInteger('id');
                // See comment on the foreign key constraint above. The same is true
                // here, too.
                $table->foreign('id')
                    ->references('id')
                    ->on('maia_annotation_candidates')
                    ->onDelete('cascade');

                $table->unsignedInteger('job_id')->index();
                $table->foreign('job_id')
                    ->references('id')
                    ->on('maia_jobs')
                    ->onDelete('cascade');

                $table->vector('vector', 384);
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
