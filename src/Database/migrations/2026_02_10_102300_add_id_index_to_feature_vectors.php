<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This migration implements a unique index for the feature vector IDs which will
     * make updateOrCreate operations in ProcessNoveltyDetectedImage and
     * ProcessObjectDetectedImage much faster.
     *
     * @return void
     */
    public function up()
    {
        // Handle duplicates in maia_training_proposal_feature_vectors
        $this->removeDuplicates('maia_training_proposal_feature_vectors');


        Schema::table('maia_training_proposal_feature_vectors', function (Blueprint $table) {
            $table->unique('id');
        });

        // Handle duplicates in maia_annotation_candidate_feature_vectors
        $this->removeDuplicates('maia_annotation_candidate_feature_vectors');

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            $table->unique('id');
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
            $table->dropUnique(['id']);
        });

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            $table->dropUnique(['id']);
        });
    }

    /**
     * Remove duplicate IDs from a table, keeping only one occurrence per ID.
     *
     * @param string $table
     * @return void
     */
    protected function removeDuplicates(string $table)
    {
        // Find duplicate IDs
        $duplicates = DB::table($table)
            ->selectRaw("id, COUNT(*) as count")
            ->groupBy('id')
            ->havingRaw("COUNT(*) > 1")
            ->get();

        // Delete all duplicate rows except one.
        foreach ($duplicates as $duplicate) {
            DB::table($table)
                ->where('id', $duplicate->id)
                ->whereNotIn('ctid', fn ($q) =>
                    $q->select('ctid')
                        ->from($table)
                        ->where('id', $duplicate->id)
                        ->orderBy('ctid', 'desc')
                        ->limit(1)
                )
                ->delete();
        }
    }
};
