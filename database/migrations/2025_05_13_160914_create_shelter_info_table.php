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
        Schema::create('shelter_info', function (Blueprint $table) {
            $table->id('shelterID');
            $table->string('logo')->nullable();
            $table->string('main_photo');
            $table->string('name');
            $table->string('name_en');
            $table->string('phone');
            $table->string('gmail');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->text('description');
            $table->text('description_en');
            $table->unsignedBigInteger('rulesID');
            $table->timestamps();

            $table->foreign('rulesID')->references('ruleID')->on('adoption_rules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelter_info');
    }
};
