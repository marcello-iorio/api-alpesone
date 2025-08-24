<?php
namespace App\Console\Commands;

use App\Models\{Brand, CarModel, Color, Fuel, Transmission, Unit, Vehicle, Version};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Importar a facade DB

class ImportVehiclesData extends Command
{
    protected $signature = 'import:vehicles';
    protected $description = 'Recebe dados do veículo da fonte e atualiza o banco de dados.
    ';
    // Troque a URL pelo arquivo local para desenvolvimento
    // private const API_URL = 'https://hub.alpes.one/api/v1/integrator/export/1902';

    //Esse json é o substituto da api porque ela está retornando erro 5000
    private const LOCAL_JSON_PATH = 'storage/app/mock.json'; // Salve o JSON aqui

    public function handle()
    {
        $this->info('Iniciando importação de dados de veículos...');

        // Para desenvolvimento, vamos ler o arquivo local
        if (!file_exists(storage_path('app/mock.json'))) {
             $this->error('Arquivo mock.json não encontrado em storage/app/');
             return 1;
        }
        $jsonData = json_decode(file_get_contents(storage_path('app/mock.json')), true);
        $items = $jsonData['data'] ?? [];

        // A lógica com HTTP e Cache ficaria aqui para a URL real

        $progressBar = $this->output->createProgressBar(count($items));
        $progressBar->start();

        // Usar uma transação para garantir a integridade dos dados
        DB::transaction(function () use ($items, $progressBar) {
            foreach ($items as $item) {
                // Validação básica
                if (empty($item['id']) || empty($item['brand'])) {
                    Log::warning('Item inválido pulado:', ['item' => $item]);
                    $progressBar->advance();
                    continue;
                }

                // 1. Encontra ou cria os registros relacionados
                $brand = Brand::firstOrCreate(['original_id' => $item['brand']['id']], ['name' => $item['brand']['name']]);
                $carModel = CarModel::firstOrCreate(['original_id' => $item['model']['id']], ['name' => $item['model']['name']]);
                $version = Version::firstOrCreate(['original_id' => $item['version']['id']], ['name' => $item['version']['name']]);
                $color = Color::firstOrCreate(['original_id' => $item['color']['id']], ['name' => $item['color']['name']]);
                $fuel = Fuel::firstOrCreate(['original_id' => $item['fuel']['id']], ['name' => $item['fuel']['name']]);
                $transmission = Transmission::firstOrCreate(['original_id' => $item['transmission']['id']], ['name' => $item['transmission']['name']]);
                $unit = Unit::firstOrCreate(['original_id' => $item['unit']['id']], ['name' => $item['unit']['name']]);

                // 2. Atualiza ou cria o veículo principal
                Vehicle::updateOrCreate(
                    ['original_id' => $item['id']], // Condição
                    [ // Dados
                        'brand_id' => $brand->id,
                        'car_model_id' => $carModel->id,
                        'version_id' => $version->id,
                        'color_id' => $color->id,
                        'fuel_id' => $fuel->id,
                        'transmission_id' => $transmission->id,
                        'unit_id' => $unit->id,
                        'status' => $item['status'],
                        'year_build' => $item['year_build'],
                        'year_model' => $item['year_model'],
                        'new' => $item['new'],
                        'km' => $item['km'],
                        'board' => $item['board'],
                        'doors' => $item['doors'],
                        'price' => $item['price'],
                        'description' => $item['description'],
                        'photos' => $item['photos'],
                        'optionals' => $item['optionals'],
                        'portals' => $item['portals'],
                        'published_portals' => $item['published_portals'],
                        'stamps' => $item['stamps'],
                    ]
                );
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->info("\nImportação concluída com sucesso!");
        return 0;
    }
}