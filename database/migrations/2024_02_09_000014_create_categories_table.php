<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();

            $table->integer('page_views')->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->integer('oldgroupid')->nullable();
            $table->integer('oldproductid')->nullable();
            $table->string('oldproductimg')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale')->index();

            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique('name','locale');

            $table->boolean('online')->default(0)->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();

            $table->softDeletes();
        });
    }
}
