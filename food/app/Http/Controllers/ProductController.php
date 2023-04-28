<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Show API details.
     *
     * @return \Illuminate\Http\JsonResponse
     */

   //  O método index recupera todos os produtos que possuem um status
   //  "publicado" do banco de dados, com paginação, e os retorna como JSON.
   //  O número de itens por página é definido pela variável $perPage e a página atual
   //  é obtida a partir do objeto de solicitação usando $request->input('page', 1).
   public function index(Request $request)
    {

        // Define a quantidade de itens por página
        $perPage = 10;

        // Recupera a página atual da URL
        $page = $request->input('page', 1);

        // Recupera os produtos da base de dados com paginação
        $products = Product::where('status', 'published')->paginate($perPage, ['*'], 'page', $page);

        // Retorna os produtos como JSON
        return response()->json($products);
    }

    // Nesse método, usamos a classe DB do Laravel para verificar se a conexão com o banco de dados está OK.
    // Em seguida, usamos o serviço de cache do Laravel para obter o horário da última execução do CRON.
    // Usamos a classe Carbon para calcular o tempo de atividade da aplicação e obtemos o uso de memória através da função memory_get_usage().
    // Por fim, retornamos um JSON com todas essas informações.

    public function apiDetails()
    {
        // Verifica se a conexão com a base de dados está OK
        try {
            DB::connection()->getPdo();
            $databaseStatus = true;
        } catch (\Exception $e) {
            $databaseStatus = false;
        }

        // Informações sobre o CRON
        $lastCronExecution = Cache::get('last_cron_execution');
        if (!$lastCronExecution) {
            $lastCronExecution = 'Nunca executado';
        }

        // Informações sobre a aplicação
        $uptime = Carbon::parse($_SERVER['REQUEST_TIME'])->diffForHumans(null, true);
        $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2) . 'MB';

        return response()->json([
            'database_status' => $databaseStatus,
            'last_cron_execution' => $lastCronExecution,
            'uptime' => $uptime,
            'memory_usage' => $memoryUsage,
        ]);
    }

    //O método show recupera um único produto pelo seu código do banco de dados e o retorna como JSON.
    //Se o produto não existir, é retornando um erro 404.
    public function show($code)
    {
        // Busca o produto pelo código
        $product = Product::where('code', $code)->first();

        // Verifica se o produto existe
        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }

        // Retorna o produto como JSON
        return response()->json($product);
    }

    // O método store cria um novo produto no banco de dados com base nos dados recebidos na solicitação.
    // Os dados são validados usando a classe Validator, e se a validação falhar,
    // é retornado um erro 400 com detalhes dos erros de validação. Se a validação for bem-sucedida,
    // uma nova instância de Produto é criada,
    // os dados são atribuídos às suas propriedades e ele é salvo no banco de dados. O produto recém-criado é então retornado como JSON.
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products',
            'status' => 'required|in:published,unpublished',
            'product_name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:255',
            'brands' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
            'labels' => 'nullable|string|max:255',
            'cities' => 'nullable|string|max:255',
            'purchase_places' => 'nullable|string|max:255',
            'stores' => 'nullable|string|max:255',
            'ingredients_text' => 'nullable|string',
            'traces' => 'nullable|string|max:255',
            'serving_size' => 'nullable|string|max:255',
            'serving_quantity' => 'nullable|numeric',
            'nutriscore_score' => 'nullable|integer',
            'nutriscore_grade' => 'nullable|string|max:1',
            'main_category' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = new Product();

        $product->code = $request->code;
        $product->status = $request->status;
        $product->product_name = $request->product_name;
        $product->quantity = $request->quantity;
        $product->brands = $request->brands;
        $product->categories = $request->categories;
        $product->labels = $request->labels;
        $product->cities = $request->cities;
        $product->purchase_places = $request->purchase_places;
        $product->stores = $request->stores;
        $product->ingredients_text = $request->ingredients_text;
        $product->traces = $request->traces;
        $product->serving_size = $request->serving_size;
        $product->serving_quantity = $request->serving_quantity;
        $product->nutriscore_score = $request->nutriscore_score;
        $product->nutriscore_grade = $request->nutriscore_grade;
        $product->main_category = $request->main_category;
        $product->image_url = $request->input('image_url');

        // Salva as alterações no produto
        $product->save();

        // Retorna o produto atualizado como JSON
        return response()->json($product);
    }

    //O método update atualiza um produto existente no banco de dados com base no código e dados recebidos na solicitação.
    //Como o método store, os dados são validados usando a classe Validator. Se a validação falhar,
    // um erro 400 é retornado com detalhes dos erros de validação. Se a validação for bem-sucedida,
    //o produto existente é recuperado do banco de dados,
    //os novos dados são atribuídos às suas propriedades e é salvo no banco de dados. O produto atualizado é então retornado como JSON.

    public function update($code, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:published,unpublished',
            'product_name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:255',
            'brands' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:255',
            'labels' => 'nullable|string|max:255',
            'cities' => 'nullable|string|max:255',
            'purchase_places' => 'nullable|string|max:255',
            'stores' => 'nullable|string|max:255',
            'ingredients_text' => 'nullable|string',
            'traces' => 'nullable|string|max:255',
            'serving_size' => 'nullable|string|max:255',
            'serving_quantity' => 'nullable|numeric',
            'nutriscore_score' => 'nullable|integer',
            'nutriscore_grade' => 'nullable|string|max:1',
            'main_category' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $product = Product::where('code', $code)->firstOrFail();

        $product->status = $request->status;
        $product->product_name = $request->product_name;
        $product->quantity = $request->quantity;
        $product->brands = $request->brands;
        $product->categories = $request->categories;
        $product->labels = $request->labels;
        $product->cities = $request->cities;
        $product->purchase_places = $request->purchase_places;
        $product->stores = $request->stores;
        $product->ingredients_text = $request->ingredients_text;
        $product->traces = $request->traces;
        $product->serving_size = $request->serving_size;
        $product->serving_quantity = $request->serving_quantity;
        $product->nutriscore_score = $request->nutriscore_score;
        $product->nutriscore_grade = $request->nutriscore_grade;
        $product->main_category = $request->main_category;
        $product->image_url = $request->input('image_url');

        // Salva as alterações no produto
        $product->save();

        // Retorna o produto atualizado como JSON
        return response()->json($product);
    }
    ### Exclusão de Produto

    //Para excluir um produto, utilizamos o método `destroy` da classe `Product`:

    public function destroy($code)
    {
        // Busca o produto pelo código
        $product = Product::where('code', $code)->first();

        // Verifica se o produto existe
        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }

        // Altera o status do produto para 'trash'
        $product->status = 'trash';

        // Salva as alterações no produto
        $product->save();

        // Retorna a mensagem de sucesso
        return response()->json(['message' => 'Produto excluído com sucesso.']);
    }
}


?>
