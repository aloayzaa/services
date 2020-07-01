<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocalAnexLoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local_anex:load_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load data to AnnexedLocal from padron_reducido_local_anexo.txt';

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
        $url = storage_path('app/local_anexo') . '/padron_reducido_local_anexo.txt';

        if(env('APP_ENV') == 'local'){
          $url = 'C:/xampp/htdocs/Laravel/anikama-servicios/storage/app/local_anexo/padron_reducido_local_anexo.txt';
        }
       
         DB::connection()->getPdo()
            ->exec("LOAD DATA LOCAL INFILE '{$url}' 
            INTO TABLE annexed_locals CHARACTER SET LATIN1 FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n' IGNORE 1  LINES 
            (loc_ruc, loc_ubigeo, loc_tipo_via, loc_nombre_via, loc_codigo_zona, loc_tipo_zona, loc_numero, loc_kilometro, loc_interior, loc_lote, loc_departamento, loc_manzana, @ultima_actualizacion)  SET ultima_actualizacion =NOW();");
         $this->info("Se cargó la base de datos correctamente");
    }
}
