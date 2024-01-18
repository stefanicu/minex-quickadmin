<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationCategoryPivotTable extends Migration
{
    public function up()
    {
        Schema::create('application_category', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id', 'application_id_fk_9405079')->references('id')->on('applications')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id', 'category_id_fk_9405079')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}
