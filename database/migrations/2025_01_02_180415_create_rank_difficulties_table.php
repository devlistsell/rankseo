<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankDifficultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_difficulties', function (Blueprint $table) {
            $table->id();
            $table->integer('rank_rang_id'); // e.g., 1, 2, 3
            $table->integer('min_score'); // Minimum score for this price range
            $table->integer('max_score'); // Maximum score for this price range
            $table->decimal('price', 8, 2); // Price for this range
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
        Schema::dropIfExists('rank_difficulties');
    }
}
