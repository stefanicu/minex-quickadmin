<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            // Drop unique constraints
            $table->dropUnique(['name', 'locale']);
            $table->dropUnique(['slug', 'locale']);
        });
    }
    
    public function down()
    {
        Schema::table('category_translations', function (Blueprint $table) {
            // Restore unique constraints if rolling back
            $table->unique(['name', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }
};
