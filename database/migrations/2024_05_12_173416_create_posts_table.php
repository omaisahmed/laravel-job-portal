<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->constrained()->nullable()->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title',256);
            $table->string('subtitle',100);
            $table->string('slug',100)->unique();
            $table->foreignId('category_id')->constrained()->nullable()->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->text('body');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('image')->nullable();
            $table->integer('like')->nullable();
            $table->integer('dislike')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
