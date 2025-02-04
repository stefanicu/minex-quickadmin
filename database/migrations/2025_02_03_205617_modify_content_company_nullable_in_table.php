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
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->longText('content')->nullable()->change();
            $table->string('company')->nullable()->change();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_translations', function (Blueprint $table) {
            $table->longText('content')->nullable(false)->change();
            $table->string('company')->nullable(false)->change();
        });
    }
};
