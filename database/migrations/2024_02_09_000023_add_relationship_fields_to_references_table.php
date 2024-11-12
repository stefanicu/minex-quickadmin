<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReferencesTable extends Migration
{
    public function up()
    {
        Schema::table('references', function (Blueprint $table) {
            $table->foreign('industries_id', 'industries_fk_9446971')
                ->references('id')
                ->on('industries')
                ->onDelete('set null'); // Optional: set to null on delete if desired
        });
    }
}
