<?php

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
        Schema::create('model_artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();

            $table->string('model_type'); // App\Models\Song | App\Models\Album etc.
            $table->unsignedBigInteger('model_id');

            $table->enum('role', ['primary', 'featured', 'producer'])->default('primary');

            $table->timestamps();

            $table->unique(['artist_id', 'model_type', 'model_id', 'role'],  'uq_model_artists');
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_artists');
    }
};
