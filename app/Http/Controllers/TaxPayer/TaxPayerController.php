<?php

namespace App\Http\Controllers\TaxPayer;

use App\TaxPayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class TaxPayerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        $this->validate($request, $rules);

        $perPage = 50;

        if($request->has('per_page')){
            $perPage = (int)$request->per_page;
        }

        return $this->showAll($request, $perPage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ruc)
    {
        $tax_payer = TaxPayer::where('emp_ruc', $ruc)->get();
        return $this->showOne($tax_payer);
    }

    public function consula_dni(Request $request){

        $request->merge(['dni' => $request->dni]);

        $rules = [
            'dni' => 'size:8'
        ];

        $this->validate($request, $rules);

        $tax_payer = TaxPayer::where('emp_ruc', 'LIKE',"10{$request->dni}%")->get();

        if($tax_payer->isEmpty()){
            $tax_payer = $this->consulta_in_reniec($request->dni);
        }
 
        return $this->showOne($tax_payer);
    }


    private function consulta_in_reniec($dni){
        $response = Http::withHeaders([
            'Requestverificationtoken' => '30OB7qfO2MmL2Kcr1z4S0ttQcQpxH9pDUlZnkJPVgUhZOGBuSbGU4qM83JcSu7DZpZw-IIIfaDZgZ4vDbwE5-L9EPoBIHOOC1aSPi4FS_Sc1:clDOiaq7mKcLTK9YBVGt2R3spEU8LhtXEe_n5VG5VLPfG9UkAQfjL_WT9ZDmCCqtJypoTD26ikncynlMn8fPz_F_Y88WFufli38cUM-24PE1',
            'Content-Type' => 'application/json;chartset=utf-8'
            ])->post('https://aplicaciones007.jne.gob.pe/srop_publico/Consulta/api/AfiliadoApi/GetNombresCiudadano', [
            'CODDNI' => $dni,
        ]);

        if($response->serverError()){
          return collect([]);
        }

        if($response->failed()){
           return collect([]);
        }
     
        $data = $response->json();
        $nombre = explode("|", $data['data']);
        if($nombre[0] == ""){
            return collect([]);
        }
        $data = [
            0 => [
                "emp_descripcion" => "{$nombre[0]} {$nombre[1]} {$nombre[2]}",
                "dni" => $dni,
            ]
        ];
        return collect($data);
    }

    public function consulta_in_reniec_opcional($dni){ //Otro servicio (no estan todos) a usar posiacaso falle el primero

        $response = Http::get('https://eldni.com/buscar-por-dni?dni='.$dni);

        $dom = new \DOMDocument();
        @$dom->loadHTML($response);
    
        $dom->strictErrorChecking = FALSE;
    
        libxml_use_internal_errors(false);
        $xml = simplexml_import_dom($dom);
    
        $nombre = $xml->xpath("//table/tbody/tr/td[@class='text-left']");
        $rtn = array();
        $cont = 0;
        foreach($nombre as $i => $obj)
        {
            $cont++;
            $rtn[$cont]  = (string)($obj[0]);
         
        }    
        dd($nombre);
        return response()->json( ['data' => ["emp_descripcion" => "{$rtn[1]} {$rtn[2]} {$rtn[3]}"]], 200);


    }

    public function consulta_dni_curl($dni){  //usando solo curl 
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://aplicaciones007.jne.gob.pe/srop_publico/Consulta/api/AfiliadoApi/GetNombresCiudadano');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{'CODDNI':'$dni'}");
        curl_setopt($ch, CURLOPT_POST, 1);
        
        $headers = array();
        $headers[] = 'Requestverificationtoken: 30OB7qfO2MmL2Kcr1z4S0ttQcQpxH9pDUlZnkJPVgUhZOGBuSbGU4qM83JcSu7DZpZw-IIIfaDZgZ4vDbwE5-L9EPoBIHOOC1aSPi4FS_Sc1:clDOiaq7mKcLTK9YBVGt2R3spEU8LhtXEe_n5VG5VLPfG9UkAQfjL_WT9ZDmCCqtJypoTD26ikncynlMn8fPz_F_Y88WFufli38cUM-24PE1';
        $headers[] = 'Content-Type: application/json;chartset=utf-8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        echo json_encode($result);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }


}
