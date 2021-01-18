<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use ZanySoft\Zip\Zip;
use Illuminate\Console\Command;
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
        $time = Carbon::now()->format('Y-m-d h:i:s A');
        $this->info("Script start {$time}");

        $url = "http://www.sunat.gob.pe/descargaPRR/padron_reducido_local_anexo.zip" ;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
        curl_close($curl);

        Storage::disk('local_anexo')->put('local_anexo.zip', $response);
        $zip = Zip::open(storage_path('app/local_anexo') . '/local_anexo.zip');
        $zip->extract(storage_path('app/local_anexo'));
        $zip->close();
        $time = Carbon::now()->format('Y-m-d h:i:s A');
        $this->info("Descarga y Descomprimido correctamente");
        $this->info("Script end {$time}");

    }
}
