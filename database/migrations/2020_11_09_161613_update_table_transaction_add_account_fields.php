<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableTransactionAddAccountFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function($table){
            $table->string('account_title_id')->nullable()->after('department_name');
            $table->string('account_title')->nullable()->after('account_title_id');
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
            $table->dropColumn(['account_title','account_title_id']);
        });
    }
}
