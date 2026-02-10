<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This migration implements a unique index for the feature vector IDs which will
     * make updateOrInsert operations in ProcessNoveltyDetectedImage and
     * ProcessObjectDetectedImage much faster.
     *
     * @return void
     */
    public function up()
    {
        // Handle duplicates in maia_training_proposal_feature_vectors
        $this->removeDuplicates('maia_training_proposal_feature_vectors');


        Schema::table('maia_training_proposal_feature_vectors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->change();
        });

        // Handle duplicates in maia_annotation_candidate_feature_vectors
        $this->removeDuplicates('maia_annotation_candidate_feature_vectors');

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->change();
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
            $table->unsignedBigInteger('id')->unique(false)->change();
        });

        Schema::table('maia_annotation_candidate_feature_vectors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique(false)->change();
        });
    }

    /**
     * Remove duplicate IDs from a table, keeping only one occurrence per ID.
     * This keeps the row with the highest ctid (most recently inserted).
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

        // Delete all duplicate rows except the one with the highest ctid (most recent)
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
