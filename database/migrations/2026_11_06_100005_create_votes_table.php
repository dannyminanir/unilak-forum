<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('votable');   // votable_type, votable_id + index
            $table->tinyInteger('value'); // 1 = upvote, -1 = downvote
            $table->timestamps();

            // A user can only vote once per entity
            $table->unique(['user_id', 'votable_type', 'votable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};

