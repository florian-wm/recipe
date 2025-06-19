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
        Schema::create('custom_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('quantity')->default('1');
            $table->string('unit')->nullable();
            $table->boolean('is_saved')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_items');
    }
};
