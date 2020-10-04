<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableTransactionAddFieldRequirementsId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function($table){
            $table->string('requirements_id')->nullable();
            $table->string('hereby_check')->nullable();
            $table->string('process_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction', function($table){
            $table->dropColumn(['requirements_id','hereby_check','process_by']);
        });
    }
}
