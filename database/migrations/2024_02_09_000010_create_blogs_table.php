<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('online')->default(0)->nullable();
            $table->integer('oldid')->nullable();
            $table->string('oldimage')->nullable();
            $table->string('oldarticletype')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blog_translations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('locale')->index();

            $table->foreignId('blog_id')->references('id')->on('blogs')->onDelete('cascade');

            $table->boolean('online')->default(0)->nullable();
            $table->string('name');
            $table->string('slug');
            $table->longText('content');
            $table->longText('image_text')->nullable();


            $table->string('oldimg1')->nullable();
            $table->string('oldimg2')->nullable();
            $table->string('oldimg3')->nullable();
            $table->string('oldcategory')->nullable();

            $table->unique(['name', 'locale']);
            $table->unique(['slug', 'locale']);
        });
    }
}
