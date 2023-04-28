<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code')->default(0);
            $table->string('status')->default('available');
            $table->timestamp('imported_t')->default(now());
            $table->string('url')->default('');
            $table->string('creator')->default('');
            $table->timestamp('created_t')->default(now());
            $table->timestamp('last_modified_t')->default(now());
            $table->string('product_name')->default('');
            $table->string('quantity')->default('');
            $table->string('brands')->default('');
            $table->string('categories')->default('');
            $table->string('labels')->default('');
            $table->string('cities')->default('');
            $table->string('purchase_places')->default('');
            $table->string('stores')->default('');
            $table->string('ingredients_text')->default('');
            $table->string('traces')->default('');
            $table->string('serving_size')->default('');
            $table->decimal('serving_quantity', 8, 2)->default(0);
            $table->integer('nutriscore_score')->default(0);
            $table->string('nutriscore_grade')->default('');
            $table->string('main_category')->default('');
            $table->string('image_url')->default('');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
