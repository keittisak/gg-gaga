<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('full_name')->nullable();
            $table->text('address')->nullable();
            $table->text('full_address')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('province_id')->unsigned()->nullable();
            $table->integer('district_id')->unsigned()->nullable();
            $table->integer('subdistrict_id')->unsigned()->nullable();
            $table->string('postalcode', 5)->nullable();
            $table->string('phone',30)->nullable();
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
        Schema::dropIfExists('customers');
    }
}
