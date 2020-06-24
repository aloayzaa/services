<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxPayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_payers', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->unique();
            $table->string('razon_social');
            $table->string('taxpayer_state');
            $table->string('address_condition');
            $table->string('ubigeo');
            $table->string('via_type');
            $table->string('via_name');
            $table->string('zone_code');
            $table->string('zone_type');
            $table->string('number');
            $table->string('interior');
            $table->string('lote');
            $table->string('departamento');
            $table->string('manzana');
            $table->string('kilometro');
            $table->string('emp_fecha');
            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_payers');
    }
}
