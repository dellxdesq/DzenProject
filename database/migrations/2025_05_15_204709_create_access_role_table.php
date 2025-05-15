<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('access_role', function (Blueprint $table) {
            $table->foreignId('access_id')->constrained('accesses')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->primary(['access_id', 'role_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('access_role');
    }
};

