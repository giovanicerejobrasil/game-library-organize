<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->string('background_image')->nullable();
            $table->text('synopsis')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable()->index();
            $table->string('developer')->nullable()->index();
            $table->string('publisher')->nullable()->index();
            $table->string('trailer_url')->nullable();
            $table->boolean('is_franchise')->default(false)->index();
            $table->string('franchise_name')->nullable()->index();
            $table->string('age_rating')->nullable();
            $table->json('purchase_links')->nullable();
            $table->json('genre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
