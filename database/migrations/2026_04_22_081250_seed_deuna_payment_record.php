<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DeunaPayment;

return new class extends Migration
{
    public function up()
    {
        if (DeunaPayment::count() == 0) {
            DeunaPayment::create([
                'status' => 0,
                'deuna_key' => '',
                'deuna_secret' => '',
                'country_code' => 'EC',
                'currency_code' => 'USD',
                'currency_rate' => 1,
            ]);
        }
    }

    public function down()
    {
        // No need to rollback unless we want to delete everything
    }
};
