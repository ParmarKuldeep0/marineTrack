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
        Schema::create('vessel_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 5, 1)->default(0);
            $table->decimal('course', 5, 1)->default(0);
            $table->decimal('heading', 5, 1)->default(0);
            $table->string('destination')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('eta')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            $table->index('received_at');
            $table->index(['latitude', 'longitude']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessel_positions');
    }
};
