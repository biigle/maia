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
        Schema::table('maia_training_proposals', function (Blueprint $table) {
            //
          $table->integer('label_id')->unsigned()->nullable();
          $table->foreign('label_id')
                ->references('id')
                ->on('labels')
                ->onDelete('restrict');
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
          $table->dropColumn(['label_id']);
        });
    }
};
