<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTable extends Migration
{
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->integer('oldid')->nullable();
            $table->string('oldimage_1')->nullable();
            $table->string('oldimage_2')->nullable();
            $table->string('oldimage_3')->nullable();
            $table->string('oldimage_4')->nullable();
            $table->string('oldimage_5')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
