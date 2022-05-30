<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('method_id')->unsigned()->nullable();
            $table->integer('bank_id')->unsigned()->nullable();
            $table->integer('statement_id')->unsigned()->nullable();
            $table->double('amount',10,4)->nullable();
            $table->string('channel')->nullable();
            $table->string('transaction_code')->nullable();
            $table->dateTime('transfered_at');
            $table->text('image')->nullable();
            $table->enum('status',['waiting', 'confirm', 'reject']);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('transaction_code');
        });

        Schema::create('order_payment', function (Blueprint $table) {
            $table->integer('order_id')->unsigned();
            $table->integer('payment_id')->unsigned();
            $table->primary(['order_id', 'payment_id']);
            $table->double('amount_by_order',10,4)->nullable();
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
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_payment');
    }
}
