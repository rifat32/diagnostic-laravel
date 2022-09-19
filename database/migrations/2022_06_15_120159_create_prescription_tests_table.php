<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("prescription_id")->nullable();
            $table->foreign('prescription_id')->references('id')->on('prescribtions')->onDelete('cascade');
            $table->string("name")->nullable();
            $table->string("type")->nullable();

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
        Schema::dropIfExists('prescription_tests');
    }
}
