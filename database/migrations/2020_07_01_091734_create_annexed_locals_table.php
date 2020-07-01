<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnexedLocalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annexed_locals', function (Blueprint $table) {
            $table->id();
            $table->string('loc_ruc')->unique();
            $table->string('loc_ubigeo');
            $table->string('loc_tipo_via');
            $table->string('loc_nombre_via');
            $table->string('loc_codigo_zona');
            $table->string('loc_tipo_zona');
            $table->string('loc_numero');
            $table->string('loc_kilometro');
            $table->string('loc_interior');
            $table->string('loc_lote');
            $table->string('loc_departamento');
            $table->string('loc_manzana');
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
        Schema::dropIfExists('annexed_locals');
    }
}
