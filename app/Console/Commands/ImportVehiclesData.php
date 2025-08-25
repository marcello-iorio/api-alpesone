<?php

namespace App\Console\Commands;

use App\Models\Vehicle; // O único Model que precisamos agora
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Importamos o Cache

class ImportVehiclesData extends Command
{
    protected $signature = 'import:vehicles';
    protected $description = 'Busca dados da API externa e atualiza o banco de dados local.';

    // URL real da API
    private const API_URL = 'https://hub.alpes.one/api/v1/integrator/export/1902';

    public function handle()
    {
        $this->info('Iniciando importação de dados de veículos via API...');

        // Lógica de Cache para respeitar o rate limit (2x a cada 30 min)
        // Tentamos lembrar da resposta por 15 minutos.
        $items = Cache::remember('api_vehicle_data', now()->addMinutes(15), function () {
            try {
                $response = Http::get(self::API_URL);

                if (!$response->successful()) {
                    $this->error('Falha ao acessar a API. Status: ' . $response->status());
                    Log::error('Falha na API', ['status' => $response->status(), 'body' => $response->body()]);
                    return null; // Retorna nulo para não salvar nada no cache em caso de erro
                }
                
                // A API retorna um array de objetos diretamente
                return $response->json();

            } catch (\Exception $e) {
                $this->error('Erro de conexão com a API: ' . $e->getMessage());
                Log::error('Erro de conexão com a API: ' . $e->getMessage());
                return null;
            }
        });

        // Se o cache estiver vazio (por falha na requisição), paramos a execução.
        if (is_null($items)) {
            $this->error('Não foi possível obter os dados da API. Abortando.');
            return 1; // Retorna código de erro
        }

        $progressBar = $this->output->createProgressBar(count($items));
        $progressBar->start();

        // Usamos uma transação para garantir a integridade
        DB::transaction(function () use ($items, $progressBar) {
            foreach ($items as $item) {
                // Validação básica para garantir que o item tem os dados mínimos
                if (empty($item['id']) || empty($item['brand'])) {
                    Log::warning('Item inválido pulado:', ['item' => $item]);
                    $progressBar->advance();
                    continue;
                }

                // Atualiza ou cria o veículo principal
                Vehicle::updateOrCreate(
                    ['id' => $item['id']], // Condição para encontrar o registro
                    [ // Dados para mapear
                        'brand'         => $item['brand'],
                        'model'         => $item['model'],
                        'version'       => $item['version'],
                        'year_build'    => $item['year']['build'],
                        'year_model'    => $item['year']['model'],
                        'transmission'  => $item['transmission'],
                        'km'            => $item['km'],
                        'price'         => $item['price'],
                        'color'         => $item['color'],
                        'fuel'          => $item['fuel'],
                        'doors'         => $item['doors'],
                        'board'         => $item['board'],
                        'description'   => $item['description'],
                        'sold'          => $item['sold'] === '1', // Converte "1" para true e "0" para false
                        'category'      => $item['category'] ?? null,
                        'photos'        => $item['fotos'] ?? [], // Note a mudança de 'photos' para 'fotos'
                        'optionals'     => $item['optionals'] ?? [],
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