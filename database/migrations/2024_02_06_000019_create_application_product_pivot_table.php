<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('application_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_9446980')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id', 'application_id_fk_9446980')->references('id')->on('applications')->onDelete('cascade');
        });
    }
}
