<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id', 'brand_fk_9198535')
                ->references('id')
                ->on('brands')
                ->onDelete('set null'); // Optional: set to null on delete if desired
        });
    }
}
