<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger("sale_id")->nullable();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade')->nullable();

            $table->unsignedBigInteger("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->nullable();
            $table->double("amount")->nullable();
            $table->double("line_discount")->nullable();
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
        Schema::dropIfExists('sale_details');
    }
}
