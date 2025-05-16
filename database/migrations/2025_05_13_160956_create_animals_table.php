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
        Schema::create('animals', function (Blueprint $table) {
            $table->id('animalID');
            $table->string('name');
            $table->string('name_en');
            $table->string('type');
            $table->string('type_en');
            $table->string('gender');
            $table->integer('age_years');
            $table->integer('age_months');
            $table->string('size');
            $table->string('size_en');
            $table->timestamp('age_updated_at');
            $table->text('additional_information')->nullable();
            $table->text('additional_information_en')->nullable();
            $table->unsignedBigInteger('shelterID');
            $table->timestamps();

            $table->foreign('shelterID')->references('shelterID')->on('shelter_info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
