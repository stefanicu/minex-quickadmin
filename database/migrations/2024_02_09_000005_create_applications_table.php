<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('applications_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->string('locale')->index();
            $table->boolean('online')->default(0)->nullable();

            $table->string('name');
            $table->string('slug');

            $table->unique('name','locale');
            $table->softDeletes();
        });
    }
}
