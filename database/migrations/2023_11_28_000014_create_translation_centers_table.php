<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationCentersTable extends Migration
{
    public function up()
    {
        Schema::create('translation_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->string('language');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('section');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
