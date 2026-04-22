<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deuna_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->string('deuna_key')->nullable();
            $table->string('deuna_secret')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency_code')->nullable();
            $table->double('currency_rate')->default(1);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('deuna_payments');
    }
};
