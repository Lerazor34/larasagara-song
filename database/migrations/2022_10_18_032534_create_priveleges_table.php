<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priveleges', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('sub_module');
            $table->string('module_name');
            $table->string('namespace');
            $table->string('ordering');
            $table->defaultColumn();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('priveleges');
    }
};
