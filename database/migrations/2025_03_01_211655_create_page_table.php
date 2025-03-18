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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('page_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('locale')->index();
            
            $table->foreignId('page_id')->references('id')->on('pages')->onDelete('cascade');
            
            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->string('call_to_action')->nullable();
            $table->string('call_to_action_link')->nullable();
            $table->longText('image_text')->nullable();
            
            $table->unique(['name', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
