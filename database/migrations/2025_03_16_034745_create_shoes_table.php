<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoesTable extends Migration {
    public function up() {
        Schema::create('shoes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size');
            $table->integer('price');
            $table->integer('stock');
            $table->string('barcode')->unique();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('shoes');
    }
}