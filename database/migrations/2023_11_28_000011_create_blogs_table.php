<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->string('language');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
