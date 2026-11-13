<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->unsignedInteger('replies_count')->default(0);     // denormalized
            $table->unsignedInteger('votes_count')->default(0);       // denormalized
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_pinned', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

