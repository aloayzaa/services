<?php

namespace App\Http\Controllers\ExchangeRate;

use App\Exchange;
use App\Http\Controllers\ApiController;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExchangeRateController extends ApiController
{
    public function show(Request $request){
        //dd($request);
        $v = Validator::make($request->all(),[
            'mounth' => 'required|numeric|min:1|max:12',
            'year'   => 'required|numeric|min:2009',
            'day'    => 'numeric|min:1|max:31'
        ]);
        if ($v->fails()){
            return response()->json(['error' => '400 Bad Request.'],400);
        }else{
            if (!$request->has('day')){
                $d1 = "{$request->year}-{$request->mounth}-1";
                $d2 = Carbon::parse($d1)->lastOfMonth()->format('Y-m-d');
                return Exchange::whereBetween('date',[$d1,$d2])->withCasts([
                    'date' => 'date:d'
                ])->get();
            }else{
                $d1 = "{$request->year}-{$request->mounth}-{$request->day}";
               return  Exchange::where('date',$d1)->withCasts([
                   'date' => 'date:d'
               ])->get();
            }
        }
    }
    //Obtiene los datos por url
    //otro comentario
    public function fullDate($year,$month,$day){
        $v = Validator::make([
                "year"=>$year,
                "mounth" =>$month,
                "day"   =>$day
            ],
            [
            'mounth' => 'required|numeric|min:1|max:12',
            'year'   => 'required|numeric|min:2009',
            'day'    => 'required|numeric|min:1|max:31'
        ]);

        if ($v->fails()){
            return response()->json(['error' => '400 Bad Request.'],400);
        }
        return  Exchange::where('date',"{$year}-{$month}-{$day}")->withCasts([
            'date' => 'date:d'
        ])->get();
    }

    public function insertData(){
        $data = $this->loadData();
        //dd($data['2009-12']->data[0]->dia);
        foreach ($data as $v => $e) {
            $time = Carbon::now()->format('h:i:s A');
            echo "\n{$v} :: {$time}\n";
            foreach ($e->data as $a => $u){
                if (isset($u->compra) && isset($u->venta)){
                    $o = new Exchange;
                    $o->date = "{$v}-{$u->dia}";
                    //echo "{$time}\n";
                    //printf("%-4u %-10.3f%-10.3f\n",$u->dia,$u->compra,$u->venta);
                    $o->compra = $u->compra;
                    $o->venta = $u->venta;
                    $o->save();
                }else{
                    break 2 ;
                }
            }
        }
        return true;
    }
    //este metodo se debe usar todos los dias para agregar el tipo de cambio
    public function today(){
        $period =  Carbon::now()->format('Y-m');
        $data = [];
        $file = "{$this->formatDate($period)}.json";
        $exists = Storage::disk('exchange')->exists($file);
        if ($exists){
            $data[$this->formatDate($period)] = json_decode(Storage::disk('exchange')->get($file));
            foreach ($data as $v => $e) {
                $time = Carbon::now()->format('h:i:s A');
                echo "\n{$v} :: {$time}\n";
                foreach ($e->data as $a => $u){
                    if (isset($u->compra) && isset($u->venta)){
                        Exchange::firstOrCreate(
                            ['date' => "{$v}-{$u->dia}"],
                            [   'compra' => $u->compra,
                                'venta' => $u->venta
                            ]
                        );
                    }else{
                        break 2 ;
                    }
                }
            }
            return true;
        }
        return  false;
    }

    public function loadData(){

        $period = CarbonPeriod::create('2009-12','1 month', Carbon::now());
        $data = [];
        // Iterate over the period
        foreach ($period as $date) {
            $file = "{$this->formatDate($date->format('Y-m'))}.json";
            $exists = Storage::disk('exchange')->exists($file);
            if ($exists) {
                $data[$this->formatDate($date->format('Y-m'))] = json_decode(Storage::disk('exchange')->get($file));
            }
        }
        // Convert the period to an array of dates
        return $data;
    }

    public function formatDate($date){
        $date = explode("-",$date);
        $y = $date[0];
        $m = $date[1];
        if (intval($m) < 10){
            $m = explode('0',$m)[1];
            return "{$date[0]}-{$m}";
        }
        return "{$y}-{$m}";
    }

}
