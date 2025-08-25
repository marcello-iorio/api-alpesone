<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http; // Usamos Http em vez de Storage
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ImportVehiclesCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o comando importa dados válidos de uma resposta HTTP simulada.
     */
    public function test_imports_data_successfully_from_http_api(): void
    {
        // 1. Arrange (Preparar)
        // Simulamos uma resposta de sucesso da API com 2 veículos.
        // A estrutura do JSON aqui deve ser idêntica à da API real.
        $fakeApiResponse = [
            [
                "id" => 125306, "brand" => "Hyundai", "model" => "CRETA", "version" => "CRETA 16A ACTION",
                "year" => ["model" => "2025", "build" => "2025"], "doors" => "5", "board" => "JCU2I93",
                "transmission" => "Automática", "km" => "24208", "description" => "Carro A", "sold" => "0",
                "category" => "SUV", "price" => "115900.00", "color" => "Branco", "fuel" => "Gasolina",
                "fotos" => [], "optionals" => []
            ],
            [
                "id" => 126801, "brand" => "GM - Chevrolet", "model" => "TRACKER", "version" => "TRACKER Premier",
                "year" => ["model" => "2021", "build" => "2021"], "doors" => "5", "board" => "RDX6A56",
                "transmission" => "Automática", "km" => "61300", "description" => "Carro B", "sold" => "0",
                "category" => "SUV", "price" => "99900.00", "color" => "Cinza", "fuel" => "Gasolina",
                "fotos" => [], "optionals" => []
            ]
        ];

        Http::fake([
            'hub.alpes.one/*' => Http::response($fakeApiResponse, 200)
        ]);

        // 2. Act (Executar)
        $this->artisan('import:vehicles')->assertExitCode(0);

        // 3. Assert (Verificar)
        $this->assertDatabaseCount('vehicles', 2);
        $this->assertDatabaseHas('vehicles', [
            'id' => 125306,
            'brand' => 'Hyundai',
            'price' => 115900.00
        ]);
    }

    /**
     * Testa se o comando ignora itens inválidos na resposta da API.
     */
    public function test_skips_invalid_data_from_api(): void
    {
        // 1. Arrange
        Log::spy(); // Prepara o Log para ser "espionado"

        // Simulamos uma resposta com um item válido e um inválido (sem 'id' ou 'brand')
        $mixedApiResponse = [
            [
                "id" => 125306, "brand" => "Hyundai", "model" => "CRETA", "version" => "CRETA 16A ACTION",
                "year" => ["model" => "2025", "build" => "2025"], "doors" => "5", "board" => "JCU2I93",
                "transmission" => "Automática", "km" => "24208", "description" => "Carro A", "sold" => "0",
                "category" => "SUV", "price" => "115900.00", "color" => "Branco", "fuel" => "Gasolina",
                "fotos" => [], "optionals" => []
            ],
            [
                "model" => "Carro sem ID" // Item inválido
            ]
        ];

        Http::fake([
            'hub.alpes.one/*' => Http::response($mixedApiResponse, 200)
        ]);

        // 2. Act
        $this->artisan('import:vehicles')->assertExitCode(0);

        // 3. Assert
        $this->assertDatabaseCount('vehicles', 1); // Apenas o válido deve ser inserido
        $this->assertDatabaseMissing('vehicles', ['model' => 'Carro sem ID']); // Garante que o inválido não foi inserido
        Log::shouldHaveReceived('warning'); // Garante que o erro foi logado
    }
}