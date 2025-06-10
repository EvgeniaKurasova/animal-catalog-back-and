<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->bigIncrements('type_id');
            $table->string('types_title');
            $table->string('types_title_en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types');
    }
}; 