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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->after('id');
            $table->unsignedBigInteger('category_id')->nullable()->after('application_id');
            
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
        
        Schema::table('product_translations', function (Blueprint $table) {
            $table->string('application_name')->nullable()->after('locale');
            $table->string('application_slug')->nullable()->after('application_name');
            $table->string('category_name')->nullable()->after('application_slug');
            $table->string('category_slug')->nullable()->after('category_name');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['application_id', 'category_id']);
        });
        
        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn(['application_name', 'application_slug', 'category_name', 'category_slug']);
        });
    }
};
