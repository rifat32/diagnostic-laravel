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
            $table->unsignedBigInteger("prescription_id");
            $table->foreign('prescription_id')->references('id')->on('prescribtions')->onDelete('cascade');
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string("product_name");
            $table->boolean("morning");
            $table->boolean("afternoon");
            $table->boolean("night");
            $table->string("end_time");
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
