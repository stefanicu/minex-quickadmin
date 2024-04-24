<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('job');
            $table->string('industry');
            $table->string('how_about');
            $table->string('message');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('country');
            $table->string('county');
            $table->string('city')->nullable();
            $table->string('checkbox')->nullable();
            $table->integer('product')->nullable();
            $table->string('ip')->nullable();;
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
