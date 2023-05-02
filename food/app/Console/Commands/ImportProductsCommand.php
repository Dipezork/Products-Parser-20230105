<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use JsonMachine\JsonMachine;

class ImportProductsCommand extends Command
{
    protected $signature = 'import:products';
    protected $description = 'Importar produtos de Open Food Facts';

    public function handle()
    {
        $filesUrl = 'https://challenges.coode.sh/food/data/json/index.txt';
        $filesList = Http::get($filesUrl)->body();
        $files = explode("\n", $filesList);

        foreach ($files as $file) {
            if (empty($file)) continue;

            $fileUrl = "https://challenges.coode.sh/food/data/json/$file";
            $response = Http::get($fileUrl);

            $json = shell_exec("curl -s $fileUrl | gzip -d | jq -n '[inputs | select(. != null)] | .[:10]'");
            $products = json_decode($json, true);

            // $json = shell_exec("cat /home/gabriel/Documentos/file.json.gz | gzip -d | jq -n '[inputs | select(. != null)] | .[:10]'");
            // $data = json_decode($json, true);

            // // Verifica se a resposta da API é válida
            // if ($response->failed()) {
            //     $this->error("Não foi possível baixar o arquivo $file");
            //     continue;
            // }

            // $response = Http::get('https://challenges.coode.sh/food/data/json/products_01.json.gz');
            // $file = '/home/gabriel/Documentos/file.json.gz';
            // file_put_contents($file, $response->getBody());
            // $uncompressedJson = shell_exec("gzip -dc $file");
            // $data = json_decode($uncompressedJson, true);
            // dd($data);

            //  $json = shell_exec("cat /home/gabriel/Documentos/file.json.gz | gzip -d | jq -n '[inputs | select(. != null)] | .[:10]'");
            // $data = json_decode($json, true);
            // dd($data);

            // $compressedJson = file_get_contents('https://challenges.coode.sh/food/data/json/products_01.json.gz');
            // $data = json_decode(gzdecode($compressedJson), true);
            // dd($data);

            // descompacta o arquivo e decodifica o JSON

              //  $compressedJson = file_get_contents('https://challenges.coode.sh/food/data/json/products_01.json.gz');



            // $compressedJson = file_get_contents('https://challenges.coode.sh/food/data/json/products_01.json.gz');
            // $json = gzdecode($compressedJson);

            // $json = gzdecode($response->body());
            // $products = json_decode($json, true);
            // $first_ten = array_slice($products, 0, 10);
            // dd($first_ten);

            // $fileUrl = "https://challenges.coode.sh/food/data/json/$file";
            // $handle = gzopen($fileUrl, 'rb');
            // $json = '';

            // // Ler as 10 primeiras linhas
            // for ($i = 0; $i < 10; $i++) {
            //     $line = gzgets($handle);
            //     if ($line === false) {
            //         break;
            //     }
            //     $json .= $line;
            // }

            // // Decodificar o JSON
            // $products = json_decode($json, true);

            // dd($products);

            // Verifica se o JSON decodificado é um array válido

            if (!is_array($products)) {
                $this->error("O arquivo $file não contém produtos");
                continue;
            }

            foreach ($products as $item) {
                $message = '';

                $existingProduct = DB::table('products')
                    ->where('code', $item['code'])
                    ->first();

                if ($existingProduct) {
                    DB::table('products')
                        ->where('id', $existingProduct->id)
                        ->update([
                           // 'url' => $item['url'],
                            'creator' => $item['creator'],
                           // 'created_t' => date('Y-m-d H:i:s', strtotime($item['created_t'])),
                           // 'last_modified_t' => date('Y-m-d H:i:s', strtotime($item['last_modified_t'])),
                            'product_name' => $item['product_name'],
                            'brands' => $item['brands'],
                            //'categories' => $item['categories'],
                            'labels' => $item['labels'],
                            'cities' => $item['cities'],
                            'purchase_places' => $item['purchase_places'],
                            'stores' => $item['stores'],
                           // 'ingredients_text' => $item['ingredients_text'],
                            'traces' => $item['traces'],
                            'serving_size' => $item['serving_size'],
                           // 'serving_quantity' => $item['serving_quantity'],
                           // 'nutriscore_score' => $item['nutriscore_score'],
                           // 'nutriscore_grade' => $item['nutriscore_grade'],
                            'main_category' => $item['main_category'],
                            'image_url' => $item['image_url'],
                            'imported_t' => date('Y-m-d H:i:s'),
                            'status' => 'published',
                        ]);

                    $message = "Product {$item['code']} updated";
                } else {
                    if (!$existingProduct) {
                        DB::table('products')->insert([
                            //'url' => $item['url'],
                            'creator' => $item['creator'],
                           // 'created_t' => date('Y-m-d H:i:s', strtotime($item['created_t'])),
                           // 'last_modified_t' => date('Y-m-d H:i:s', strtotime($item['last_modified_t'])),
                            'product_name' => $item['product_name'],
                            'brands' => $item['brands'],
                            //'categories' => $item['categories'],
                            'labels' => $item['labels'],
                            'cities' => $item['cities'],
                            'purchase_places' => $item['purchase_places'],
                            'stores' => $item['stores'],
                           // 'ingredients_text' => $item['ingredients_text'],
                            'traces' => $item['traces'],
                            'serving_size' => $item['serving_size'],
                           // 'serving_quantity' => $item['serving_quantity'],
                           // 'nutriscore_score' => $item['nutriscore_score'],
                            //'nutriscore_grade' => $item['nutriscore_grade'],
                            'main_category' => $item['main_category'],
                            'image_url' => $item['image_url'],
                            'imported_t' => date('Y-m-d H:i:s'),
                            'status' => 'published',
                        ]);
                        $message = "Product {$item['code']} created";
                    }

                    $this->line($message);
                }
            }
        }
    }
}
