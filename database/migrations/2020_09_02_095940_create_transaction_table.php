<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('code')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('document_reference_code')->nullable();
            $table->string('processing_fee_code')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('application_id')->nullable();
            $table->string('application_name')->nullable();
            $table->string('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('regional_id')->nullable();
            $table->string('regional_name')->nullable();
            $table->string('is_received_copy')->default(0)->nullable();


            $table->string('processing_fee')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_option')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default("UNPAID")->nullable();
            $table->string('transaction_status')->default("PENDING")->nullable();
            $table->string('total_amount')->nullable();
            $table->string('convenience_fee')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('amount')->nullable();
            $table->text('eor_url')->nullable();

            $table->string('application_payment_reference')->nullable();
            $table->string('application_payment_type')->nullable();
            $table->string('application_payment_option')->nullable();
            $table->string('application_payment_method')->nullable();
            $table->string('application_payment_status')->default("UNPAID")->nullable();
            $table->string('application_transaction_status')->default("PENDING")->nullable();
            $table->string('application_total_amount')->nullable();
            $table->string('application_convenience_fee')->nullable();
            $table->timestamp('application_payment_date')->nullable();
            $table->text('application_eor_url')->nullable();
            
            $table->string('processor_user_id')->nullable();
            $table->string('status')->nullable()->default('PENDING');
            $table->timestamp('application_date')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->text('remarks')->nullable();
            $table->string('is_printed_requirements')->default(0)->nullable();
            $table->string('is_resent')->default(0)->nullable();
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
        Schema::dropIfExists('transaction');
    }
}
