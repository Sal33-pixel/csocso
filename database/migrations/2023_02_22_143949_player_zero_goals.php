<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *Amikor egy meccset rögzítek akkor kell menteni ide, így nyomonkövethető ki mennyit mászott szezononként
     * @return void
     */
    public function up()
    {
        Schema::create('player_zero_goals', function (Blueprint $table) {
            $table->id();
            $table->integer('championship_id');
            $table->integer('match_id');
            $table->integer('gamer_id');
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
        Schema::dropIfExists('player_zero_goals');
    }
};
