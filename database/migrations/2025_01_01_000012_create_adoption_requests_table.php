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
            $table->id('request_id');
            $table->unsignedBigInteger('animal_id');
            $table->string('animal_name');
            $table->foreignId('user_id')->nullable()
                ->constrained('users', 'user_id')
                ->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->text('message')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->text('comment')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();

            $table->foreign('animal_id')->references('animal_id')->on('animals')->onDelete('cascade');
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
