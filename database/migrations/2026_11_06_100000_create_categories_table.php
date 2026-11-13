<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['course', 'department', 'interest']);
            $table->text('description')->nullable();
            $table->string('icon', 10)->nullable();         // emoji or short code
            $table->string('color', 7)->default('#1E3A5F'); // hex
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('type');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

