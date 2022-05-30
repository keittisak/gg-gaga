<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('name_en')->nullable();
            // $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description_en')->nullable();
            $table->text('short_description_en')->nullable();
            $table->integer('brand_id')->unsigned()->nullable();
            $table->text('image')->nullable();
            $table->integer('gallery_id')->unsigned()->nullable();
            $table->enum('type',['simple','variable']);
            $table->enum('status',['active','inactive']);
            $table->text('tags')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            // $table->index('slug');
        });

        Schema::create('skus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('shortname')->nullable();
            $table->integer('product_id')->unsigned();
            $table->string('barcode', 13)->nullable();
            $table->text('image')->nullable();
            $table->string('call_unit')->nullable();
            $table->double('full_price',10,4)->nullable();
            $table->double('price',10,4)->nullable();
            $table->double('cost',10,4)->nullable();
            $table->enum('status',['active','inactive']);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            // $table->primary('sku');
        });

        // Schema::create('variants', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('product_id')->unsigned();
        //     $table->string('name');
        //     $table->string('name_en');
        //     $table->integer('created_by')->unsigned()->nullable();
        //     $table->integer('updated_by')->unsigned()->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('options', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('variant_id')->unsigned();;
        //     $table->string('name');
        //     $table->string('name_en');
        //     $table->integer('created_by')->unsigned()->nullable();
        //     $table->integer('updated_by')->unsigned()->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('option_sku', function (Blueprint $table) {
        //     $table->string('sku', 30);
        //     $table->integer('option_id')->unsigned();
        //     $table->primary(['sku', 'option_id']);
        //     $table->integer('created_by')->unsigned()->nullable();
        //     $table->integer('updated_by')->unsigned()->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('skus');
        // Schema::dropIfExists('variants');
        // Schema::dropIfExists('options');
        // Schema::dropIfExists('option_sku');
    }
}
