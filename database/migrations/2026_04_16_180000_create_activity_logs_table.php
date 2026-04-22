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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->string('workout_type');
            $table->unsignedInteger('steps')->default(0);
            $table->unsignedInteger('runs')->default(0);
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->string('source')->default('manual');
            $table->string('provider')->nullable();
            $table->string('notes', 500)->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'activity_date']);
            $table->index(['user_id', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
