<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('department_id')->nullable();
            $table->string('name')->nullable();
            $table->string('has_processing_fee')->default(0)->nullable();
            $table->string('processing_fee')->nullable();
            $table->string('processing_days')->nullable();
            $table->string('requirements_id')->nullable();
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
        Schema::dropIfExists('application');
    }
}
