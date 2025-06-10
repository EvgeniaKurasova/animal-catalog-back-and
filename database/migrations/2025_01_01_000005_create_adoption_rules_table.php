<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_rules', function (Blueprint $table) {
            $table->id('rule_id');
            $table->text('rules');
            $table->text('rules_en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_rules');
    }
}; 