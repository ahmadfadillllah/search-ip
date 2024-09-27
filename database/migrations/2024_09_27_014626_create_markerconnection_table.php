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
        Schema::create('markerconnection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_marker_id')->constrained('marker')->cascadeOnDelete();
            $table->foreignId('to_marker_id')->constrained('marker')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markerconnection');
    }
};
