<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale');

            $table->foreignId('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('company');
            $table->longText('content');
            $table->string('name');
            $table->string('job');
        });
    }
}
