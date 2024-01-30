<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->string('language')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->nullable()->unique();
            $table->longText('description')->nullable();
            $table->longText('specifications')->nullable();
            $table->longText('advantages')->nullable();
            $table->longText('usage')->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
