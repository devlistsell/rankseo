<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankRangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_rangs', function (Blueprint $table) {
            $table->id();
            $table->string('rank_label'); // e.g., '0-1', '2-4'
            $table->integer('min_value'); // Minimum value of the range
            $table->integer('max_value'); // Maximum value of the range
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_rangs');
    }
}
