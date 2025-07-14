<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
    {
        Schema::create('cotizacion_grupals', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->foreignId('museum_id')->constrained()->onDelete('cascade');
            $table->time('start_hour');
            $table->time('end_hour');
            $table->integer('total_people');
            $table->integer('total_people_discount');
            $table->integer('total_people_whitout_discount');
            $table->decimal('total_whit_discount', 8, 2);
            $table->decimal('total_whitout_discount', 8, 2);
            $table->decimal('price_total', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cotizacion_grupals');
    }
};
