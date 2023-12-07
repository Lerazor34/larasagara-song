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
        Schema::create('sysparams', function (Blueprint $table) {
            $table->id();

            $table->string('group')->nullable();
            $table->string('key')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->string('long_value')->nullable();

            $table->defaultColumn();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysparams');
    }
};
