<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('category_id')->nullable(); // Add foreign key
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->boolean('online')->default(0)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('filter_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('locale')->index();
            
            $table->foreignId('filter_id')->references('id')->on('filters')->onDelete('cascade');
            
            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('filter_translations');
        Schema::dropIfExists('filters');
    }
};
