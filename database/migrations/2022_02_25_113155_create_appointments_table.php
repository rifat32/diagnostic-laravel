<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->date("date")->nullable();
            $table->string("remarks")->nullable();
            $table->string("status")->nullable();
            $table->text("problem")->nullable();

            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
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
        Schema::dropIfExists('appointments');
    }
}
