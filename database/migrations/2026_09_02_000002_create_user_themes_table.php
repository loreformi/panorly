<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('preset')->default('midnight');
            $table->string('accent_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('background_image_path')->nullable();
            $table->string('layout_density')->default('comfortable');
            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_themes');
    }
};
