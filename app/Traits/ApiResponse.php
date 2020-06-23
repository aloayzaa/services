<?php

namespace App\Traits;

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


    protected function filterData(Collection $collection)
	{
		foreach (request()->query() as $query => $value) {

            if($query == 'name' && $value != ''){
                $collection = $collection->filter(function ($item) use ($value) {
                    // replace stristr with your choice of matching function
                    return false !== stristr($item->name, $value);
                });
            }
		}
		return $collection;
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