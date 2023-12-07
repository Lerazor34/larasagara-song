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
        Schema::create('songs_artists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artists_id')->constrained();
            $table->foreignId('songs_id')->constrained();
            $table->defaultColumn();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};