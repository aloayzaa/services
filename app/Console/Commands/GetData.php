<?php

namespace App\Console\Commands;

use ZanySoft\Zip\Zip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax_payers:get_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Donwload and Unzip';

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

        $response = Http::get('http://www2.sunat.gob.pe/padron_reducido_ruc.zip');
        Storage::disk('padron_reducido')->put('padron_reducido_ruc.zip', $response->body());   
        
        
      //$zip = Zip::open(public_path() . '/storage/padron_reducido_ruc.zip');
   
 

        $zip = Zip::open(storage_path('app/padron_reducido') . '/padron_reducido_ruc.zip');
        $zip->extract(public_path() . '/images');
        $zip->close();  
        
        $this->info("Descarga y Descomprimido bien");

        
    }
}
