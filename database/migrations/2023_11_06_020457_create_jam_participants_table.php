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
        Schema::create('jam_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jam_session_id');
            $table->foreignId('role_id')->constrained('roles');
            $table->foreignId('skill_level_id')->constrained('skill_levels');
            $table->text('message')->nullable();
            $table->unsignedBigInteger('instrument_id')->nullable();
            $table->timestamps();

            // Add a unique index for `jam_session_id` and `user_id` columns
            $table->unique(['jam_session_id', 'user_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jam_session_id')->references('id')->on('jam_sessions')->onDelete('cascade');

            $table->foreign('instrument_id')
                ->references('id')->on('instruments')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_participants');
    }
};
