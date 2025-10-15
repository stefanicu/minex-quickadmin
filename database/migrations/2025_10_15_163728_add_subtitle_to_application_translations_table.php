<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('application_translations', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
        });
    }
    
    public function down()
    {
        Schema::table('application_translations', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
    }
};
