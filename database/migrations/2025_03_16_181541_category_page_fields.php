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
        Schema::table('category_translations', function (Blueprint $table) {
            $table->longText('content')->nullable();
            $table->string('call_to_action')->nullable();
            $table->string('call_to_action_link')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn('content');
            $table->dropColumn('call_to_action');
            $table->dropColumn('call_to_action_link');
        });
    }
};
