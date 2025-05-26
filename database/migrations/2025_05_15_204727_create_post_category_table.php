<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('post_category', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->primary(['article_id', 'category_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('post_category');
    }
};
