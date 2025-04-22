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
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade'); // Зв'язок з твариною
            $table->string('first_name'); // Ім'я
            $table->string('last_name'); // Прізвище
            $table->string('phone'); // Номер телефону
            $table->string('email'); // Електронна пошта
            $table->text('message')->nullable(); // Додаткове повідомлення
            $table->string('city')->nullable(); // місто
            $table->boolean('is_processed')->default(false); // Чи переглянутий запит
            $table->boolean('is_archived')->default(false); // Додаємо поле для архівування
            $table->text('comment')->nullable(); // Коментар адміністратора
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
