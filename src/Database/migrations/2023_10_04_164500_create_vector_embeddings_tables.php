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
            ->create('maia_training_proposal_embeddings', function (Blueprint $table) {
                // Don't use increments() because it should throw an error if this is not
                // provided.
                $table->unsignedInteger('id');
                $table->vector('embedding', 384);

                $table->primary('id');
            });

        Schema::connection('pgvector')
            ->create('maia_annotation_candidate_embeddings', function (Blueprint $table) {
                // Don't use increments() because it should throw an error if this is not
                // provided.
                $table->unsignedInteger('id');
                $table->vector('embedding', 384);

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
            ->dropIfExists('maia_training_proposal_embeddings');
        Schema::connection('pgvector')
            ->dropIfExists('maia_annotation_candidate_embeddings');
    }
}
