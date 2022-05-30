<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_name')->nullable();
            $table->enum('product_type',['simple','variable']);
            $table->integer('sku_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('call_unit')->nullable();
            $table->double('full_price',10,4)->nullable();
            $table->double('price',10,4)->nullable();
            $table->double('cost',10,4)->nullable();
            $table->integer('quantity')->default(0);
            $table->double('discount_amount', 10, 4)->default(0);
            $table->double('total_amount',10,4)->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
