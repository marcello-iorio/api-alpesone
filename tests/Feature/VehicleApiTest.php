<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    // Antes de cada teste, simulamos a API externa e rodamos nosso importador
    // para popular o banco de dados de teste de forma consistente.
    protected function setUp(): void
    {
        parent::setUp();

        // Simulamos a API externa para que o comando de importação não a acesse de verdade
        Http::fake([
            'hub.alpes.one/*' => Http::response(
                json_decode(file_get_contents(base_path('1902.json')), true), // Usamos o JSON real como base
                200
            )
        ]);

        // Rodamos o comando para popular o banco de teste
        $this->artisan('import:vehicles');
    }

    public function test_get_vehicles_endpoint_returns_a_successful_paginated_response(): void
    {
        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200);
        $response->assertJsonStructure([ // Verifica a estrutura da paginação
            'data', 'links', 'meta'
        ]);
        $response->assertJsonCount(15, 'data'); // Paginação padrão de 15
    }

    public function test_pagination_can_be_customized_via_query_parameter(): void
    {
        $response = $this->getJson('/api/vehicles?per_page=5');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data'); // Verifica se o parâmetro 'per_page' foi respeitado
        $response->assertJsonPath('meta.per_page', 5);
    }
    
    public function test_get_single_vehicle_endpoint_returns_correct_data(): void
    {
        $vehicle = Vehicle::first();
        $response = $this->getJson('/api/vehicles/' . $vehicle->id);

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $vehicle->id);
        $response->assertJsonPath('data.brand', $vehicle->brand);
    }

    public function test_get_non_existent_vehicle_returns_a_404_not_found_error(): void
    {
        $nonExistentId = 999999;
        $response = $this->getJson('/api/vehicles/' . $nonExistentId);

        $response->assertStatus(404);
        $response->assertJson([ // Verifica nossa mensagem de erro customizada
            'message' => 'O recurso solicitado não foi encontrado.'
        ]);
    }

    public function test_can_create_a_new_vehicle(): void
    {
        $newVehicleData = [
            "id" => 999999, "brand" => "Teste", "model" => "Modelo Teste", "version" => "1.0",
            "year" => ["model" => "2025", "build" => "2025"], "doors" => "4", "board" => "TST1A23",
            "transmission" => "Manual", "km" => "10", "description" => "Teste de criação", "sold" => "0",
            "category" => "Teste", "price" => "50000.00", "color" => "Azul Teste", "fuel" => "Flex"
        ];
        
        // No teste de criação, precisamos dos dados "planos", sem o objeto 'year'
        $postData = collect($newVehicleData)->except('year')->merge([
            'year_build' => $newVehicleData['year']['build'],
            'year_model' => $newVehicleData['year']['model'],
        ])->toArray();

        $response = $this->postJson('/api/vehicles', $postData);

        $response->assertStatus(201); // 201 Created
        $this->assertDatabaseHas('vehicles', ['id' => 999999, 'brand' => 'Teste']);
    }

    public function test_can_update_a_vehicle(): void
    {
        $vehicle = Vehicle::first();
        $updateData = ['km' => 98765, 'price' => '12345.67'];
        
        $response = $this->patchJson('/api/vehicles/' . $vehicle->id, $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'km' => 98765]);
    }
    
    public function test_can_delete_a_vehicle(): void
    {
        $vehicle = Vehicle::first();
        $response = $this->deleteJson('/api/vehicles/' . $vehicle->id);

        $response->assertStatus(204); // 204 No Content
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }
}