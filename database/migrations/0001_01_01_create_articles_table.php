<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('description')->nullable(); // Краткое описание
            $table->string('preview_path')->nullable(); // Путь к превью-изображению

            $table->timestamp('created_date')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->timestamp('edit_date')->nullable();
            $table->timestamp('delete_date')->nullable();
            $table->boolean('is_publish')->default(false);

            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('last_editor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
