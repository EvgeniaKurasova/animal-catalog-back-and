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
            $table->id('animal_id');
            $table->string('name');
            $table->string('name_en');
            $table->unsignedBigInteger('type_id');
            $table->boolean('gender')->default(false);
            $table->integer('age_years');
            $table->integer('age_months');
            $table->unsignedBigInteger('size_id');
            $table->timestamp('age_updated_at');
            $table->string('sterilization')->nullable();
            $table->string('sterilization_en')->nullable();
            $table->text('additional_information')->nullable();
            $table->text('additional_information_en')->nullable();
            $table->timestamps();
            $table->foreign('type_id')->references('type_id')->on('types')->onDelete('restrict');
            $table->foreign('size_id')->references('size_id')->on('sizes')->onDelete('restrict');
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