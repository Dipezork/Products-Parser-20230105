<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     $response = [];
//     $response['status'] = 'OK';
//     $response['read_db'] = DB::connection()->getDatabaseName() ? 'OK' : 'ERROR';
//     try {
//         DB::connection()->getDoctrineSchemaManager()->listTableNames();
//         $response['write_db'] = 'OK';
//     } catch (\Exception $e) {
//         $response['write_db'] = 'ERROR';
//     }
//     $response['last_cron'] = '2023-04-28 12:00:00'; // Substitua pelo horário da última execução do CRON
//     $response['uptime'] = round((microtime(true) - LARAVEL_START) / 60, 2) . ' minutes'; // Cálculo do tempo de uptime
//     $response['memory'] = round(memory_get_usage() / 1024 / 1024, 2) . ' MB'; // Cálculo do uso de memória
//     return response()->json($response);
// });



//import
Route::get('/import', 'App\Http\Controllers\ImportController@import');

Route::get('/import-data', 'App\Http\Controllers\ImportController@importData');

Route::get('/import-data-ger', 'App\Http\Controllers\ProductController@importData');

// Route::put('/products/{code}', function ($code) {
//     $product = Product::where('code', $code)->first();
//     if ($product) {
//         // Atualizar os campos do produto com os dados recebidos
//         $product->update(request()->all());
//         return response()->json(['status' => 'OK', 'message' => 'Produto atualizado com sucesso']);
//     } else {
//         return response()->json(['status' => 'ERROR', 'message' => 'Produto não encontrado'], 404);
//     }
// });


