<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomesTable extends Migration
{
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('language');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('first_text')->nullable();
            $table->longText('seccond_text')->nullable();
            $table->longText('quote')->nullable();
            $table->string('button')->nullable();
            $table->timestamps();
        });
    }
}
