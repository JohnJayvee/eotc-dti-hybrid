<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('mname')->nullable();
            $table->string('region')->nullable();
            $table->string('region_name')->nullable();
            $table->string('town')->nullable();
            $table->string('town_name')->nullable();
            $table->string('barangay')->nullable();
            $table->string('barangay_name')->nullable();
            $table->string('street_name')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('phic_no')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
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
        Schema::dropIfExists('customer');
    }
}
