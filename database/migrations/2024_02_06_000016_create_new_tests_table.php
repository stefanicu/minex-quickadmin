<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTestsTable extends Migration
{
    public function up()
    {
        Schema::create('new_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nume')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
