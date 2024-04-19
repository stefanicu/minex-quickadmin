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
            $table->integer('oldid')->nullable();
            $table->string('oldimage_1')->nullable();
            $table->string('oldimage_2')->nullable();
            $table->string('oldimage_3')->nullable();
            $table->string('oldimage_4')->nullable();
            $table->string('oldimage_5')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reference_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->foreignId('reference_id')->references('id')->on('references')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->longText('content');
            $table->string('text_img1')->nullable();
            $table->string('text_img2')->nullable();
            $table->string('text_img3')->nullable();
            $table->string('text_img4')->nullable();
            $table->string('text_img5')->nullable();
        });
    }
}
