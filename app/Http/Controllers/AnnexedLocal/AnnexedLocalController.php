<?php

namespace App\Http\Controllers\AnnexedLocal;

use App\AnnexedLocal;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AnnexedLocalController extends ApiController
{
    public function show($cadena)  //ahora es para ruc y dni
    {
        $tax_payer = AnnexedLocal::where('loc_ruc', $cadena)->get();
        return $this->showOne($tax_payer);
    }
}
