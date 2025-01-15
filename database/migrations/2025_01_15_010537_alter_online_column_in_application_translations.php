<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('application_translations', function (Blueprint $table) {
            $table->tinyInteger('online')->default(1)->nullable(false)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_translations', function (Blueprint $table) {
            $table->tinyInteger('online')->default(0)->nullable()->change();
        });
    }
};
