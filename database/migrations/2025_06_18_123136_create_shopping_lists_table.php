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
        Schema::create('shopping_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de l'ingrédient/élément
            $table->string('quantity')->default('1'); // Quantité
            $table->string('unit')->nullable(); // Unité (kg, g, l, etc.)
            $table->boolean('is_checked')->default(false); // Si l'élément a été trouvé
            $table->text('notes')->nullable(); // Notes optionnelles
            $table->string('source')->nullable(); // Source (menu, manuel, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_lists');
    }
};
