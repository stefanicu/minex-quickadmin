<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToHomesTable extends Migration
{
    public function up()
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->unsignedBigInteger('home_id')->nullable();
            $table->foreign('home_id', 'home_fk_9450114')->references('id')->on('home_ids');
        });
    }
}
