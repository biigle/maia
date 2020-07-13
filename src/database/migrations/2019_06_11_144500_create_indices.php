<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maia_annotation_candidates', function (Blueprint $table) {
            $table->index('annotation_id');
            $table->index('label_id');
            $table->index('job_id');
        });

        Schema::table('maia_training_proposals', function (Blueprint $table) {
            $table->index('job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maia_training_proposals', function (Blueprint $table) {
            $table->dropIndex(['job_id']);
        });

        Schema::table('maia_annotation_candidates', function (Blueprint $table) {
            $table->dropIndex(['annotation_id']);
            $table->dropIndex(['label_id']);
            $table->dropIndex(['job_id']);
        });
    }
}
