<?php

namespace App\Console\Commands;

use ZanySoft\Zip\Zip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LocalAnexGetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local_anex:get_data';

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
        $response = Http::get('http://www.sunat.gob.pe/descargaPRR/padron_reducido_local_anexo.zip');
        Storage::disk('local_anexo')->put('local_anexo.zip', $response->body());   
        
        
      //$zip = Zip::open(public_path() . '/storage/padron_reducido_ruc.zip');
   
        $zip = Zip::open(storage_path('app/local_anexo') . '/local_anexo.zip');
        $zip->extract(storage_path('app/local_anexo'));
        $zip->close();  
        
        $this->info("Descarga y Descomprimido correctamente");

    }
}
