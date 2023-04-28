<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ImportHistory;

class ImportController extends Controller
{
    public function import()
    {
        $files = Http::get('https://challenges.coode.sh/food/data/json/index.txt')->body();
        $files = explode("\n", $files);
        $dataFields = Http::get('https://challenges.coode.sh/food/data/json/data-fields.txt')->body();
        $dataFields = explode("\n", $dataFields);

        $importHistory = new Product();
        $importHistory->imported_at = Carbon::now();
        $importHistory->save();

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

        return response()->json(['message' => 'Import completed successfully.']);
    }


}

?>
