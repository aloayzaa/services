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

      
    /*     if($request->has('address_condition')){
            $address_condition = $request->address_condition;
            $tax_payers = TaxPayer::where('address_condition', $address_condition)->get();
            $tax_payers = $this->paginate($tax_payers, $perPage);
            return $tax_payers; 
        }

        if($request->has('razon_social')){
            $razon_social = $request->razon_social;
            $tax_payers = TaxPayer::where('address_condition', 'LIKE', "%{$razon_social}%")->get();
            $tax_payers = $this->paginate($tax_payers, $perPage);
            return $tax_payers; 
        } */

        $query = TaxPayer::query();

        $query->when(request('address_condition'), function ($q) use ($request) {
            return $q->where('address_condition', $request->address_condition);
        });
        $query->when(request('razon_social'), function ($q) use ($request){  //MAYUSCULAS
            return $q->where('razon_social', 'LIKE', "%{$request->razon_social}%")->get();
        });

        $tax_payers = $query->get();

        $tax_payers = $this->paginate($tax_payers, $perPage);

      //  $tax_payers = TaxPayer::paginate($perPage);
        return $tax_payers;
    }

/*     protected function paginate(Collection $collection){
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);
        
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        if(request()->has('per_page')){
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

        $paginated ->appends(request()->all());
        return $paginated;
    } */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ruc)
    {
        $tax_payer = TaxPayer::where('ruc', $ruc)->get();
        return $tax_payer;
    }

    public function consula_dni($dni){

        $rules = [
            'dni' => 'size:8'
        ];

        $this->validate($request, $rules);

        $tax_payer = TaxPayer::where("10{$request->razon_social}")->get();
        return $tax_payer;
    }
}
