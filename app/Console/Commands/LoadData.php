<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax_payers:load_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load data to tax_payers from patron_reducido.txt';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       // $contents = 'C:/Users/d4ni3/downloads/padron_reducido_ruc/padron_reducido_ruc.txt';

       $url = storage_path('app/padron_reducido') . '/padron_reducido_ruc.txt';

       if(env('APP_ENV') == 'local'){
         $url = 'C:/xampp/htdocs/Laravel/anikama-servicios/storage/app/padron_reducido/padron_reducido_ruc.txt';
       }
      
        DB::connection()->getPdo()
           ->exec("LOAD DATA LOCAL INFILE '{$url}' 
           INTO TABLE tax_payers CHARACTER SET LATIN1 FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n' IGNORE 1  LINES 
           (emp_ruc, emp_descripcion, emp_estado_con, emp_con_domicilio, emp_ubigeo, emp_tipo_via, emp_nombre_via, emp_codigo_zona, emp_tipo_zona, emp_numero, emp_interior, emp_lote, emp_departamento, emp_manzana, emp_kilometro, @ultima_actualizacion)  SET ultima_actualizacion =NOW();");
        $this->info("Se cargó la base de datos correctamente");
    }
}
