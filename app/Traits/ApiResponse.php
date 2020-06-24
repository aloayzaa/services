<?php

namespace App\Traits;

use App\TaxPayer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse{

    private function successResponse($data, $code){
        return response()->json($data, $code);
    }
    
    protected function showAll(Collection $collection, $code = 200){

        if($collection->isEmpty()){
            return $this->successResponse(['data' => $collection], $code);
        }

        $collection = $this->filterData($collection);
        $collection = $this->paginate($collection);


        return $this->successResponse($collection, $code);
    }


//foreach (request()->query() as $query => $value) {

    protected function filterData($request, $perPage)
	{
        $query = TaxPayer::query();

        $query->when(request('address_condition'), function ($q) use ($request) {
            return $q->where('address_condition', $request->address_condition);
        });
        $query->when(request('razon_social'), function ($q) use ($request){  //MAYUSCULAS
            return $q->where('razon_social', 'LIKE', "%{$request->razon_social}%")->get();
        });

        $tax_payers = $query->get();

        $tax_payers = $this->paginate($tax_payers, $perPage);

        return $tax_payers;
    }
    
    protected function paginate(Collection $collection, $perPage){
   
        $page = LengthAwarePaginator::resolveCurrentPage();

        if(request()->has('per_page')){
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

        $paginated ->appends(request()->all());
        return $paginated;
    }
}