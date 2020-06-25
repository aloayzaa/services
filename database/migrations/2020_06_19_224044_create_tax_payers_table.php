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
            $table->string('emp_ruc')->unique();
            $table->string('emp_descripcion');
            $table->string('emp_estado_con');
            $table->string('emp_con_domicilio');
            $table->string('emp_ubigeo');
            $table->string('emp_tipo_via');
            $table->string('emp_nombre_via');
            $table->string('emp_codigo_zona');
            $table->string('emp_tipo_zona');
            $table->string('emp_numero');
            $table->string('emp_interior');
            $table->string('emp_lote');
            $table->string('emp_departamento');
            $table->string('emp_manzana');
            $table->string('emp_kilometro');
            $table->string('ultima_actualizacion');
            $table->engine = 'MyISAM';
            $table->charset = 'latin1';	
            $table->collation = 'latin1_general_ci';
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
