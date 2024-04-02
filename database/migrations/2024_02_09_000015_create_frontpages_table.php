<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontPagesTable extends Migration
{
    public function up()
    {
        Schema::create('front_pages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
        });

        Schema::create('front_page_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale')->index();

            $table->foreignId('front_page_id')->references('id')->on('front_pages')->onDelete('cascade');

            $table->string('name');
            $table->longText('first_text')->nullable();
            $table->longText('second_text')->nullable();
            $table->longText('quote')->nullable();
            $table->string('button')->nullable();

            $table->unique(['name', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('front_pages');
    }
}
