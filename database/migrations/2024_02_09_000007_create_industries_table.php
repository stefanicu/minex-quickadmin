<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustriesTable extends Migration
{
    public function up()
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('industry_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->foreignId('industry_id')->references('id')->on('industries')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug');

            $table->unique(['name', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }
}
