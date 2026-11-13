<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->string('department')->nullable();    // null = all departments
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable(); // null = draft
            $table->timestamps();

            $table->index(['is_pinned', 'published_at']);
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

