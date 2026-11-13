<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_type', 20);            // pdf, docx, pptx, zip, etc.
            $table->unsignedBigInteger('file_size')->default(0);  // bytes
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'created_at']);
            $table->index('file_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};

