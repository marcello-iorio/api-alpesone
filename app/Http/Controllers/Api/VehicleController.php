<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega os veículos com seus relacionamentos para evitar múltiplas queries
        $vehicles = Vehicle::with(['brand', 'carModel', 'version', 'color', 'unit'])
            ->orderBy('original_id', 'asc')
            ->paginate(15);

        // Retorna a coleção formatada pelo VehicleResource
        return VehicleResource::collection($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // A implementação do 'create' pode ser feita futuramente.
        return response()->json(['message' => 'Endpoint not implemented yet.'], 501);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        // Carrega todos os relacionamentos para a resposta detalhada
        $vehicle->load(['brand', 'carModel', 'version', 'color', 'unit', 'fuel', 'transmission']);

        // Retorna um único recurso formatado
        return new VehicleResource($vehicle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        // A implementação do 'update' pode ser feita futuramente.
        return response()->json(['message' => 'Endpoint not implemented yet.'], 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->noContent(); // Retorno padrão para delete bem-sucedido
    }
}