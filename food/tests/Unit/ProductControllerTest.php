<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    //criando uma instância do aplicativo.
    public function createApplication()
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    //Esse código carrega as variáveis de ambiente e inicializa a aplicação antes dos testes serem executados.
    public function setUp(): void
    {
        parent::setUp();

        $this->app->useEnvironmentPath(__DIR__ . '/../..');
        $this->app->bootstrapWith(['\Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables']);
    }

    public function testIndex()
    {
        $user = new User;
        $user->name = 'João Silva';
        $user->email = 'joao.silva@example.com';
        $user->password = bcrypt('123456');
        $user->save();

        // Cria uma instância de ProductController a partir do usuário criado
        $productController = $this->actingAs($user)->app->make(ProductController::class);


        $request = new Request(['page' => 1]);

        $response = $productController->index($request);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $this->assertArrayHasKey('data', json_decode($response->getContent(), true));
    }

    public function testShow()
    {
        // Cria um produto manualmente
        $product = new Product;
        $product->status= 'Disponivel';
        $product->code = '001';
        $product->save();

        // Cria um usuário manualmente
        $user = new User;
        $user->name = 'João Silva';
        $user->email = 'joao.silva@example.com';
        $user->password = bcrypt('123456');
        $user->save();

        // Cria uma instância de ProductController a partir do usuário criado
        $productController = $this->actingAs($user)->app->make(ProductController::class);

        // Chama a função show com o código do produto criado
        $response = $productController->show($product->code);

        // Verifica se o status da resposta é 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Verifica se o conteúdo da resposta é um JSON válido
        $this->assertJson($response->getContent());

    }
}
