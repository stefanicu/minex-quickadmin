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
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->text('oldmoreimages')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->longText('specifications')->nullable();
            $table->longText('advantages')->nullable();
            $table->longText('usages')->nullable();
            $table->longText('accessories')->nullable();

            $table->unique(['name', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }
}
