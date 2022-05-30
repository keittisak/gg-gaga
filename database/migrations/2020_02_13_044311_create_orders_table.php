<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 30)->uniqid();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('total_quantity')->default(0);
            $table->double('total_amount', 10,4)->default(0);
            $table->double('discount_amount', 10,4)->default(0);
            $table->double('shipping_fee',10,4)->default(0);
            $table->double('overpay',10,4)->default(0);
            $table->double('net_total_amount',10,4)->default(0);
            $table->text('shipping_full_name')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('shipping_full_address')->nullable();
            $table->integer('shipping_country_id')->unsigned()->nullable();
            $table->integer('shipping_province_id')->unsigned()->nullable();
            $table->integer('shipping_district_id')->unsigned()->nullable();
            $table->integer('shipping_subdistrict_id')->unsigned()->nullable();
            $table->string('shipping_postalcode', 5)->nullable();
            $table->string('shipping_phone', 30)->nullable();
            $table->enum('status',['draft', 'unpaid', 'transfered', 'packing', 'paid', 'shipped', 'voided']);
            $table->enum('sale_channel',['line', 'facebook', 'instagram', 'other']);
            $table->integer('payment_method_id')->nullable();
            $table->integer('shipment_method_id')->nullable();
            $table->string('tracking_code')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
