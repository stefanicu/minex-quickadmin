<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationIdCategoryPivotTable extends Migration
{
    public function up()
    {
        Schema::create('application_id_category', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id_id');
            $table->foreign('application_id_id', 'application_id_id_fk_9450121')->references('id')->on('application_ids')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id', 'category_id_fk_9450121')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}
