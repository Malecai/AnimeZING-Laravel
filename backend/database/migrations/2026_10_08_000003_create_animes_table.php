<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animes', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->json('genres');
            $table->decimal('score', 4, 2);
            $table->string('image_url', 2048);
            $table->text('synopsis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
