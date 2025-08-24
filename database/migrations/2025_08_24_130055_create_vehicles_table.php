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
            $table->id();
            $table->unsignedBigInteger('original_id')->unique();

            // Chaves estrangeiras que se conectam com as tabelas acima
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('car_model_id')->constrained('car_models');
            $table->foreignId('version_id')->constrained('versions');
            $table->foreignId('color_id')->constrained('colors');
            $table->foreignId('fuel_id')->constrained('fuels');
            $table->foreignId('transmission_id')->constrained('transmissions');
            $table->foreignId('unit_id')->constrained('units');

            // Demais campos do JSON
            $table->integer('status');
            $table->year('year_build'); // O tipo 'year' é mais apropriado aqui
            $table->year('year_model');
            $table->boolean('new');
            $table->unsignedInteger('km');
            $table->string('board')->nullable();
            $table->unsignedTinyInteger('doors');
            $table->decimal('price', 10, 2); // 'decimal' é ideal para valores monetários
            $table->text('description')->nullable();

            // Colunas do tipo JSON para armazenar arrays e objetos complexos
            $table->json('photos')->nullable();
            $table->json('optionals')->nullable();
            $table->json('portals')->nullable();
            $table->json('published_portals')->nullable();
            $table->json('stamps')->nullable();

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
