<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToHomesTable extends Migration
{
    public function up()
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->unsignedBigInteger('idd_id')->nullable();
            $table->foreign('idd_id', 'idd_fk_9450106')->references('id')->on('home_indices');
        });
    }
}
