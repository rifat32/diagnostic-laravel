<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionCCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_c_c_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("prescription_id");
            $table->foreign('prescription_id')->references('id')->on('prescribtions')->onDelete('cascade');
            $table->string("name");
            $table->string("value");
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
        Schema::dropIfExists('prescription_c_c_s');
    }
}
