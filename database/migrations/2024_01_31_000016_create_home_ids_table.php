<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeIdsTable extends Migration
{
    public function up()
    {
        Schema::create('home_ids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
        });
    }
}
