<?php

namespace App\Http\Controllers\ExchangeRate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function show(Request $request){
 
        if(!$request->has('mounth') && !$request->has('year')){
            $response = Http::get('http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias');
        }else{
            $response = Http::get('http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes='.$request->mounth.'&anho='.$request->year.'&accion=init');
        }
        
        if($response->failed()){
            return response()->json(['errors' => array('detalle' => 'Error en la conexión con Sunat')], 400);
        }

        $dom = new \DOMDocument();
        @$dom->loadHTML($response);


        $dom->strictErrorChecking = FALSE;

        libxml_use_internal_errors(false);
        $xml = simplexml_import_dom($dom);

        $fecha = $xml->xpath("//center/h3"); //titulo de fecha sunat
        $dias = $xml->xpath("//table/tr/td[@class='H3']");
        $compra_venta = $xml->xpath("//table/tr/td[@class='tne10']");

        $rtn = array();

      //  $periodo = $request->period.'-'.$request->year;  // mes - año

        if( !empty($fecha) )
        {
            $periodo = (string)$fecha[0]; //titulo de la fecha a consumir
        }
        if( !empty($dias) && !empty($compra_venta) && count((array)$dias) == count((array)$compra_venta)/2 )
        {
            foreach($dias as $i => $obj)
            {
                $rtn[$i]['dia'] = str_pad(trim((string)$obj->strong),2,0,STR_PAD_LEFT);
                //$rtn[$i]['fecha'] = str_pad(trim((string)$obj->strong),2,0,STR_PAD_LEFT) . '/'. $mes.'/'.$anio;
            }
            $cont = 0;
            foreach($compra_venta as $i=>$obj)
            {
                if( ($i+1)%2==0 )
                {
                    $rtn[$cont]['venta'] = trim((string)$obj);
                    $cont++;
                }
                else
                {
                    $rtn[$cont]['compra'] = trim((string)$obj);
                }
            }
        }

        $collect = collect($rtn);
        if($collect->isEmpty()){

            return response()->json(['errors' => array('detalle' => 'No existe data para esa fecha')],422);

        }else {

            $codigo = $request->year.$request->period;

            $compra = 0.00;
            $venta = 0.00;

            $ultimosunat = $collect->last();
            $final = collect();
            for ($i = 1; $i <= 31; $i++) {

                if($i > $ultimosunat['dia'] ){
                break;
                  }

                $item = $collect->firstWhere('dia', $i);

             //   dd($item);
                if($item){

                    $final->push($item);

                    $compra = $item['compra'];
                    $venta = $item['venta'];

                }else{

                    $final->push([
                        'dia' => str_pad($i, 2, "0", STR_PAD_LEFT),
                        'compra' => $compra,
                        'venta' => $venta]
                    );

                }
            }
     

        }

        return $final;
    }
}
