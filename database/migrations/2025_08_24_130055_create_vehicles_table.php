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
    Schema::create('vehicles', function (Blueprint $table) {
        
         $table->unsignedBigInteger('id')->primary();

        
        $table->string('brand');
        $table->string('model');
        $table->string('version');
        $table->string('color');
        $table->string('fuel');
        $table->string('transmission');

        
        $table->string('category')->nullable();
        $table->boolean('sold')->default(false);

        
        $table->string('year_build');
        $table->string('year_model');
        $table->integer('km');
        $table->string('board')->nullable();
        $table->integer('doors');
        $table->decimal('price', 10, 2);
        $table->text('description')->nullable();
        $table->json('photos')->nullable();
        $table->json('optionals')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
