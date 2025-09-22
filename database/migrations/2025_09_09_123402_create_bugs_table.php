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
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('steps_to_reproduce')->nullable();
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('status')->default('open'); // open, in_progress, testing, resolved, closed
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->json('screenshots')->nullable(); // Array of screenshot paths
            $table->json('ai_summary')->nullable(); // AI-generated summary
            $table->string('source')->default('manual'); // manual, automatic, api
            $table->text('log_data')->nullable(); // For automatically created bugs
            $table->json('metadata')->nullable(); // Additional bug metadata
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
