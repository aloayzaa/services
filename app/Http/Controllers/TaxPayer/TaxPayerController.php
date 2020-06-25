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
        $tax_payer = TaxPayer::where('emp_ruc', $ruc)->first();
        return $this->showOne($tax_payer);
    }

    public function consula_dni(Request $request){

        $request->merge(['dni' => $request->dni]);

        $rules = [
            'dni' => 'size:8'
        ];

        $this->validate($request, $rules);

        $tax_payer = TaxPayer::where('emp_ruc', 'LIKE',"10{$request->dni}%")->first();
        return $this->showOne($tax_payer);
    }
}
