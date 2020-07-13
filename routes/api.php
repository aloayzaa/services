<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('client')->group(function () {
    //TaxPayer
    Route::get('contribuyentes', 'TaxPayer\TaxPayerController@index');
    Route::get('consulta_ruc/{ruc}', 'TaxPayer\TaxPayerController@show');
    Route::get('consulta_dni/{dni}', 'TaxPayer\TaxPayerController@consula_dni');

    //AnnexedLocal
    Route::get('locales-anexos/{ruc}', 'AnnexedLocal\AnnexedLocalController@show');
    
    //ExchangeRate
    Route::get('tcambio', 'ExchangeRate\ExchangeRateController@show'); 
});

