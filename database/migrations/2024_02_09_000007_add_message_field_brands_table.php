<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageFieldBrandsTable extends Migration
{
    public function up()
    {
        Schema::table('brand_translations', function (Blueprint $table) {
            $table->string('offline_message')->nullable();
        });
    }
}
