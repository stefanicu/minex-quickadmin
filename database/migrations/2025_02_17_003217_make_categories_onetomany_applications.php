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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->after('id'); // Add foreign key
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
        
        DB::table('categories')->update([
            'application_id' => DB::raw('(SELECT application_id FROM (select application_id from products where category_id = categories.id LIMIT 1) as a)')
        ]);
        
        Schema::dropIfExists('application_category');
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the pivot table `application_category`
        Schema::create('application_category', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('category_id');
            
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->primary(['application_id', 'category_id']);
        });
        
        // Repopulate the pivot table from the `categories` table
        DB::table('application_category')->insertUsing(
            ['application_id', 'category_id'],
            DB::raw('SELECT application_id, id as category_id FROM categories WHERE application_id IS NOT NULL')
        );
        
        // Drop the `application_id` column from `categories`
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
        });
    }
};
