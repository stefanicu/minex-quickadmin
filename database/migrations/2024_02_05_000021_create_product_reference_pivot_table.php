<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReferencePivotTable extends Migration
{
    public function up()
    {
        Schema::create('product_reference', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_id_fk_9446964')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('reference_id');
            $table->foreign('reference_id', 'reference_id_fk_9446964')->references('id')->on('references')->onDelete('cascade');
        });
    }
}
