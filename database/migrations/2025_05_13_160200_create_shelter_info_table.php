<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shelter_info', function (Blueprint $table) {
            $table->id('shelter_id');
            $table->string('logo')->nullable();
            $table->string('main_photo');
            $table->string('name');
            $table->string('name_en');
            $table->string('phone');
            $table->string('email');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->text('description');
            $table->text('description_en');
            $table->unsignedBigInteger('rule_id');
            $table->timestamps();

            $table->foreign('rule_id')->references('rule_id')->on('adoption_rules');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shelter_info');
    }
}; 