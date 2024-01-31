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
        Schema::table('maia_training_proposal_feature_vectors', function (Blueprint $table) {
            // Delete queries were terribly slow with this constraint. Also, individual
            // proposals could not be deleted anyway. This is dropped to make the delete
            // query (cascaded from deletion of the whole MAIA job) more efficient.
            //
            // See also comment in: 2023_12_12_150900_create_feature_vectors_tables.php
            $table->dropForeign(['id']);
        });

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            // See comment above. This is the same.
            $table->dropForeign(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maia_training_proposal_feature_vectors', function (Blueprint $table) {
            $table->foreign('id')
                ->references('id')
                ->on('maia_training_proposals')
                ->onDelete('cascade');
        });

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            $table->foreign('id')
                ->references('id')
                ->on('maia_annotation_candidates')
                ->onDelete('cascade');
        });
    }
};
