<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductfilesTable extends Migration
{
    public function up()
    {
        Schema::create('productfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->string('languages')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('productfile_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->foreignId('productfile_id')->references('id')->on('productfiles')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('title')->nullable();
        });
    }
}
