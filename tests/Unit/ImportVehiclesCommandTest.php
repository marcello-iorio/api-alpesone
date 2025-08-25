<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportVehiclesCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste para o "caminho feliz": o comando importa dados válidos com sucesso.
     */
    public function test_imports_data_successfully_from_json(): void
    {
        // 1. Arrange (Preparar o ambiente)
        // Criamos um disco de armazenamento falso e um mock.json com 2 veículos válidos.
        Storage::fake('local');
        $validJsonData = '{
            "data": [
                { "id": 1001, "brand": { "id": 1, "name": "Fiat" }, "model": { "id": 201, "name": "Argo" }, "version": { "id": 101, "name": "1.0" }, "color": { "id": 4, "name": "Branco" }, "fuel": { "id": 5, "name": "Flex" }, "transmission": { "id": 1, "name": "Manual" }, "unit": { "id": 1, "name": "Loja 1" }, "status": 1, "year_build": "2019", "year_model": "2020", "new": false, "km": "45000", "board": "QWE1R23", "doors": "4", "price": 68000.00, "description": "Carro A", "photos": [], "optionals": [], "portals": {}, "published_portals": {}, "stamps": [] },
                { "id": 1002, "brand": { "id": 2, "name": "Chevrolet" }, "model": { "id": 202, "name": "Onix" }, "version": { "id": 102, "name": "1.0 Turbo" }, "color": { "id": 11, "name": "Preto" }, "fuel": { "id": 5, "name": "Flex" }, "transmission": { "id": 3, "name": "Automatica" }, "unit": { "id": 2, "name": "Loja 2" }, "status": 1, "year_build": "2021", "year_model": "2021", "new": false, "km": "25000", "board": "RTY4U56", "doors": "4", "price": 85000.00, "description": "Carro B", "photos": [], "optionals": [], "portals": {}, "published_portals": {}, "stamps": [] }
            ]
        }';
        Storage::disk('local')->put('mock.json', $validJsonData);


        // 2. Act (Executar a ação)
        // Rodamos o comando Artisan e verificamos se ele termina com sucesso (código 0).
        $this->artisan('import:vehicles')->assertExitCode(0);


        // 3. Assert (Verificar os resultados)
        // Verificamos se o banco de dados contém o que esperamos.
        $this->assertDatabaseCount('vehicles', 2);
        $this->assertDatabaseCount('brands', 2);
        $this->assertDatabaseHas('vehicles', [
            'original_id' => 1001,
            'price' => 68000.00,
            'description' => 'Carro A',
        ]);
    }

    /**
     * Teste de validação: o comando ignora dados inválidos e registra um aviso.
     */
    public function test_skips_invalid_data_and_logs_warning(): void
    {
        // 1. Arrange
        // Criamos um espião (spy) para o sistema de Log.
        Log::spy();
        
        // Criamos um JSON com um item válido e um inválido (faltando a marca).
        Storage::fake('local');
        $invalidJsonData = '{
            "data": [
                { "id": 1001, "brand": { "id": 1, "name": "Fiat" }, "model": { "id": 201, "name": "Argo" }, "version": { "id": 101, "name": "1.0" }, "color": { "id": 4, "name": "Branco" }, "fuel": { "id": 5, "name": "Flex" }, "transmission": { "id": 1, "name": "Manual" }, "unit": { "id": 1, "name": "Loja 1" }, "status": 1, "year_build": "2019", "year_model": "2020", "new": false, "km": "45000", "board": "QWE1R23", "doors": "4", "price": 68000.00, "description": "Carro A", "photos": [], "optionals": [], "portals": {}, "published_portals": {}, "stamps": [] },
                { "id": 1003 }
            ]
        }';
        Storage::disk('local')->put('mock.json', $invalidJsonData);
        
        
        // 2. Act
        $this->artisan('import:vehicles')->assertExitCode(0);


        // 3. Assert
        // Apenas o item válido deve ter sido inserido no banco.
        $this->assertDatabaseCount('vehicles', 1);
        $this->assertDatabaseMissing('vehicles', ['original_id' => 1003]);

        // Verificamos se o método 'warning' do Log foi chamado.
        Log::shouldHaveReceived('warning');
    }
}