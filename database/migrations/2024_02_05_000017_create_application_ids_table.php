<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationIdsTable extends Migration
{
    public function up()
    {
        Schema::create('application_ids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
        });
    }
}
