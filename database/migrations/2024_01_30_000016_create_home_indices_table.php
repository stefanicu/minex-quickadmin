<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeIndicesTable extends Migration
{
    public function up()
    {
        Schema::create('home_indices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
        });
    }
}
