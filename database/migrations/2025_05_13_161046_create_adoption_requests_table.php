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
            $table->id('requestID');
            $table->unsignedBigInteger('animalID');
            $table->unsignedBigInteger('userID');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('gmail');
            $table->text('message')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('animalID')->references('animalID')->on('animals')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
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
