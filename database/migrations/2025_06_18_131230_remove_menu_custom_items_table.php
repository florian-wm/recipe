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
        // Supprimer la table menu_custom_items
        Schema::dropIfExists('menu_custom_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la table menu_custom_items si nécessaire
        Schema::create('menu_custom_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('quantity')->default('1');
            $table->string('unit')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }
};
