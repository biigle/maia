<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionModelColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maia_jobs', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->boolean('has_model')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maia_jobs', function (Blueprint $table) {
            $table->dropColumn(['description', 'has_model']);
        });
    }
}
