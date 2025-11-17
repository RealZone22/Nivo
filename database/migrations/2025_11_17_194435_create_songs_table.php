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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('duration'); // seconds
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('is_public')->default(true);

            $table->string('external_source')->nullable(); // e.g. youtube, soundcloud etc.
            $table->string('external_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
