<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ImportHistory;

use GuzzleHttp\Client;

class ImportController extends Controller
{

    public function importData()
    {
        // Cria o cliente GuzzleHttp
        $client = new Client();

        // Obtém a lista de arquivos do Open Food Facts
        $filesResponse = $client->get('https://challenges.coode.sh/food/data/json/index.txt');
        $files = [];
        if ($filesResponse->getStatusCode() === 200) {
            $files = explode("\n", $filesResponse->getBody());
        }

        // Importa somente os 100 primeiros registros de cada arquivo
        $limit = 100;

        // Lista secundária de controle dos históricos das importações
        $imported = collect();

        // Percorre os arquivos e importa os produtos
        foreach ($files as $file) {
            if (!is_string($file) || empty($file)) {
                continue;
            }

            // Obtém o conteúdo do arquivo JSON
            $jsonResponse = $client->get("https://challenges.coode.sh/food/data/json/{$file}");
            $json = [];
            if ($jsonResponse->getStatusCode() === 200) {
                $json = json_decode($jsonResponse->getBody());
            }

            // Contador de produtos importados
            $count = 0;

            // Percorre os produtos do arquivo e os importa
            if (is_iterable($json)) {
                foreach ($json as $item) {
                    // Limita o número de produtos importados
                    if ($count >= $limit) {
                        break;
                    }

                    // Verifica se o item já foi importado antes
                    if ($imported->contains('code', $item->code)) {
                        continue;
                    }


                    // Cria um array com os dados do produto para serem inseridos no banco
                    $data = [
                        'code' => $item->code,
                        'status' => 'imported',
                        'imported_t' => now(),
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'brands' => $item->brands,
                        'categories' => $item->categories,
                        'labels' => $item->labels,
                        'cities' => $item->cities,
                        'purchase_places' => $item->purchase_places,
                        'stores' => $item->stores,
                        'ingredients_text' => $item->ingredients_text,
                        'traces' => $item->traces,
                        'serving_size' => $item->serving_size,
                        'serving_quantity' => $item->serving_quantity,
                        'nutriscore_score' => $item->nutriscore_score,
                        'nutriscore_grade' => $item->nutriscore_grade,
                        'main_category' => $item->main_category,
                        'image_url' => $item->image_url
                    ];

                    // Insere o produto no banco de dados
                    DB::table('products')->insert($data);

                    // Registra o item como importado
                    $imported->push([
                        'code' => $item->code,
                        'status' => 'imported',
                        'imported_t' => now(),
                    ]);

                    // Incrementa o contador de produtos importados
                    $count++;
                }
            }
        }

        // Retorna mensagem de sucesso
        return response()->json(['message' => 'Importação realizada com sucesso']);
    }

    public function import()
    {
        $files = Http::get('https://challenges.coode.sh/food/data/json/index.txt')->body();
        $files = explode("\n", $files);
        $dataFields = Http::get('https://challenges.coode.sh/food/data/json/data-fields.txt')->body();
        $dataFields = explode("\n", $dataFields);

        foreach ($files as $file) {
            $file = trim($file);
            if (!empty($file)) {

                $data = Http::get('https://challenges.coode.sh/food/data/json/' . $file)->json();

                if (is_array($data)) {
                    $count = 0;
                    foreach ($data as $item) {
                        if ($count >= 100) {
                            break;
                        }
                        $product = new Product();
                        $product->code = $item['code'];
                        $product->status = $item['status'];
                        $product->imported_t = $item['imported_t'];
                        $product->url = $item['url'];
                        $product->creator = $item['creator'];
                        $product->created_t = $item['created_t'];
                        $product->last_modified_t = $item['last_modified_t'];
                        $product->product_name = $item['product_name'];
                        $product->quantity = $item['quantity'];
                        $product->brands = $item['brands'];
                        $product->categories = $item['categories'];
                        $product->labels = $item['labels'];
                        $product->cities = $item['cities'];
                        $product->purchase_places = $item['purchase_places'];
                        $product->stores = $item['stores'];
                        $product->ingredients_text = $item['ingredients_text'];
                        $product->traces = $item['traces'];
                        $product->serving_size = $item['serving_size'];
                        $product->serving_quantity = $item['serving_quantity'];
                        $product->nutriscore_score = $item['nutriscore_score'];
                        $product->nutriscore_grade = $item['nutriscore_grade'];
                        $product->main_category = $item['main_category'];
                        $product->image_url = $item['image_url'];
                        $product->save();
                        $count++;
                    }
                }
            }
        }

        return response()->json(['message' => 'Importado com sucesso.']);
    }


}

?>
