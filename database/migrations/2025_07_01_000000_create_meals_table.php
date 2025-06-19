<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->string('day'); // Ex: Lundi, Mardi, etc.
            $table->string('label'); // Ex: Déjeuner, Dîner, Petit-déjeuner, etc.
            $table->unsignedTinyInteger('order')->default(0); // Pour l'ordre dans la journée
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
}; 