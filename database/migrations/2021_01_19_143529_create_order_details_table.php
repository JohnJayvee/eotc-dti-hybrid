<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->date('request_time')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('designation_number')->nullable();
            $table->string('no_of_pages')->nullable();
            $table->string('no_of_copies')->nullable();
            $table->string('company_name')->nullable();
            $table->string('order_title')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('unit_no')->nullable();
            $table->string('street_name')->nullable();
            $table->string('brgy')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('sector')->nullable();
            $table->string('purpose')->nullable();
            $table->string('price')->nullable();
            $table->string('payment_status')->default("PENDING")->nullable();
            $table->string('created_transaction')->default("0")->nullable();
            
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
        Schema::dropIfExists('order_details');
    }
}
