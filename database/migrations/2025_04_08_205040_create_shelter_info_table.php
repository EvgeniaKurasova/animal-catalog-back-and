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
            $table->id();
            $table->string('logo')->nullable(); // Шлях до логотипу
            $table->string('name'); // Назва притулку
            $table->string('name_en'); // Назва притулку англійською
            $table->text('description'); // Опис притулку
            $table->text('description_en'); // Опис притулку англійською
            $table->timestamps();
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
