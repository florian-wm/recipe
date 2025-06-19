<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('menu_recipes', function (Blueprint $table) {
        $table->unsignedInteger('portion')->default(1);
    });
}

public function down()
{
    Schema::table('menu_recipes', function (Blueprint $table) {
        $table->dropColumn('portion');
    });
}
};
