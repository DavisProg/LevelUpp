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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('strength', ['F', 'E', 'D', 'C', 'B', 'A', 'S'])->default('F');
            $table->enum('constitution', ['F', 'E', 'D', 'C', 'B', 'A', 'S'])->default('F');
            $table->enum('intelligence', ['F', 'E', 'D', 'C', 'B', 'A', 'S'])->default('F');
            $table->enum('charisma', ['F', 'E', 'D', 'C', 'B', 'A', 'S'])->default('F');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['strength', 'constitution', 'intelligence', 'charisma']);
        });
    }
};