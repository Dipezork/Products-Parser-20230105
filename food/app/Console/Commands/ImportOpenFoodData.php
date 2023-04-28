<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Product;

//Este comando obtém a lista de arquivos disponíveis do Open Food Facts a partir do arquivo index.txt e
//importa os dados de cada arquivo para a base de dados. Ele também verifica se é hora de importar os dados,
//com base na configuração definida no arquivo de configuração. Além disso, ele limita a importação a 100 produtos de cada arquivo.

class ImportOpenFoodData extends Command
{
    protected $signature = 'import:openfood';

    protected $description = 'Imports the latest data from Open Food Facts.';

    public function handle()
    {
        // Define a URL base para os arquivos do Open Food Facts
        $urlBase = 'https://challenges.coode.sh/food/data/json/';

        // Obtém a lista de arquivos disponíveis a partir do arquivo index.txt
        $fileList = Http::get($urlBase . 'index.txt')->body();
        $fileList = explode("\n", $fileList);

        // Obtém o horário de execução definido no arquivo de configuração
        $importTime = config('app.openfood_import_time');

        // Verifica se é hora de importar os dados
        if (Carbon::now()->format('H:i') !== $importTime) {
            $this->info('It is not time to import the data yet.');
            return;
        }

        if (is_array($fileList)) {
            foreach ($fileList as $filename) {
                // Obtém o conteúdo do arquivo atual
                $fileContent = Http::get($urlBase . $filename)->body();
                $products = collect(json_decode($fileContent));

                // Limita a importação a 100 produtos
                $products = $products->take(100);

                // Importa os produtos para a base de dados
                foreach ($products as $product) {
                    $existingProduct = Product::where('code', $product->code)->first();

                    if (!$existingProduct) {
                        $existingProduct = new Product;
                        $existingProduct->code = $product->code;
                    }

                    $existingProduct->name = $product->product_name;
                    $existingProduct->image_url = $product->image_url;
                    $existingProduct->description = $product->generic_name;
                    $existingProduct->imported_t = Carbon::now();
                    $existingProduct->status = 'publicado';
                    $existingProduct->save();
                }
            }
        } else {
            $this->info('No file available to import.');
        }


        $this->info('The data has been imported successfully.');
    }
}
