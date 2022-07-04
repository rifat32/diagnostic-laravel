<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("prescription_id")->nullable();
            $table->foreign('prescription_id')->references('id')->on('prescribtions')->onDelete('cascade');
            $table->unsignedBigInteger("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->nullable();
            $table->string("product_name")->nullable();
            $table->boolean("morning")->nullable();
            $table->boolean("afternoon")->nullable();
            $table->boolean("night")->nullable();
            $table->string("end_time")->nullable();
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
        Schema::dropIfExists('prescription_medicines');
    }
}
