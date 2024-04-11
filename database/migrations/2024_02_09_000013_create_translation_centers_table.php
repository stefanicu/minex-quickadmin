<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslationCentersTable extends Migration
{
    public function up()
    {
        Schema::create('translation_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('translation_center_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale')->index();

            $table->foreignId('translation_center_id')->references('id')->on('translation_centers')->onDelete('cascade');

            $table->string('name');
        });
    }
}
