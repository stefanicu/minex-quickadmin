<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id', 'brand_fk_9198535')->references('id')->on('brands');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreign('reference_id', 'reference_fk_9198571')->references('id')->on('references');
        });
    }
}
