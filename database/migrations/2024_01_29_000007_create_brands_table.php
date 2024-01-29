<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->string('language');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
