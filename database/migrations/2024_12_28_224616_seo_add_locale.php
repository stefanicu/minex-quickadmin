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
        Schema::table('seo', function (Blueprint $table) {
            $table->string('locale')->after('model_id')->index();
            $table->unique(['model_id', 'model_type', 'locale']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo', function (Blueprint $table) {
            // Drop the unique index
            $table->dropUnique(['model_id', 'model_type', 'locale']);
            
            // Drop the 'locale' column
            $table->dropColumn('locale');
        });
    }
};
