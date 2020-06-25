<?php

namespace App\Traits;

use App\TaxPayer;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse{

    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function showOne(Model $model, $code = 200){

        return $this->successResponse(['data' => $model], $code);
    }
    
    protected function showAll($request, $perPage){

        if(!$request->has('emp_con_domicilio') && !$request->has('emp_descripcion') ){
            return $tax_payers = TaxPayer::paginate($perPage);
        }

        if($request->has('emp_descripcion') && strlen($request->emp_descripcion) < 3 ){
            return $tax_payers = TaxPayer::paginate($perPage);
        }

        if($request->has('emp_con_domicilio') && strlen($request->emp_con_domicilio) < 3 ){
            return $tax_payers = TaxPayer::paginate($perPage);
        }
     
        return $tax_payers = $this->filterData($request, $perPage);

    }

    protected function filterData($request, $perPage)
	{
        $query = TaxPayer::query();

        $query->when(request('emp_con_domicilio'), function ($q) use ($request) {
            return $q->where('emp_con_domicilio', $request->emp_con_domicilio);
        });
        $query->when(request('emp_descripcion'), function ($q) use ($request){  //MAYUSCULAS
            return $q->where('emp_descripcion', 'LIKE', "{$request->emp_descripcion}%")->get();
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