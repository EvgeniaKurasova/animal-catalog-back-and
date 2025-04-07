<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
/**    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
*/
    public function up(): void
{
    Schema::create('animals', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ім'я тварини
        $table->string('name_en'); // Ім'я англійською
        $table->string('gender'); // Стать (наприклад: male, female)
        $table->string('gender_en'); // Стать англійською
        $table->integer('age'); // Вік тварини у тижнях
        $table->string('size')->nullable(); // Розмір (малий, середній, великий) - необов’язково
        $table->string('size_en')->nullable(); // Розмір англійською
        $table->string('city')->nullable(); // Місто, де знаходиться тварина - необов’язково
        $table->string('city_en')->nullable(); // Місто англійською
        $table->text('description'); // Опис тварини
        $table->text('description_en'); // Опис англійською
        $table->timestamps(); // created_at та updated_at
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
