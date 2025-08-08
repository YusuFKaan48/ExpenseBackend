<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Kullanıcıya ait olanlar için
            $table->string('symbol');
            $table->timestamps();
        });
        DB::table('currencies')->insert([
            [ 'symbol' => '₺'],
            ['symbol' => '$'],
            ['symbol' => '€'],
            ['symbol' => '£'],
            [ 'symbol' => '¥'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }

};
