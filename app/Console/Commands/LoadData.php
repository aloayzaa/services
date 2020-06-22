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
           (ruc, razon_social, taxpayer_state, address_condition, ubigeo, via_type, via_name, zone_code, zone_type, number, interior, lote, departamento, manzana, kilometro, @emp_fecha)  SET emp_fecha =NOW();");

        $this->info("Se cargó la base de datos correctamente");
    }
}
