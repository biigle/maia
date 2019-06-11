<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationCandidateIndex extends Migration
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maia_annotation_candidates', function (Blueprint $table) {
            $table->dropIndex(['annotation_id']);
        });
    }
}
