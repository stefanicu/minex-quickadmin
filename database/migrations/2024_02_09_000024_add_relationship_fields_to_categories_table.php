<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('product_image_id', 'product_image_fk_9252551')
                ->references('id')
                ->on('products')
                ->onDelete('set null'); // Optional: set to null on delete if desired
        });
    }
}
