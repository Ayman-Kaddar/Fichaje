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
        Schema::create('descans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('pausa');
            $table->dateTime('continuitat')->nullable();
            $table->foreignId('fixtage_id')->constrained('fitxatge');
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
        Schema::dropIfExists('descans');
    }
};
