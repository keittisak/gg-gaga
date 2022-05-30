<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_log', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url')->nullable();
            $table->text('query_string')->nullable();
            $table->string('method', 50)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_name', 100)->nullable();
            $table->integer('is_ajax');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_log');
    }
}
