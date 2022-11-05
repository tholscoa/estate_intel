<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;

class CrudService{
    public static function create($my_model, $params){
        try{
            $record = $my_model::create($params);
        }catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while creating record'];
        }
        return [true, $record];
    }

    public static function update($my_model, $params, $id){
        try{
            $record = $my_model::where('id', $id)->first();
            if(empty($record)){
                return [false, 'record not found'];
            }
            $record->update($params);

        }catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while updating record'];
        }
        return [true, $record];
    }

    public static function delete($my_model, $id){
        try{
            $record = $my_model::where('id', $id)->first();
            if(empty($record)){
                return [false, 'record not found'];
            }
            $record->delete();
        }catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while deleting record'];
        }
        return [true, $record['name']];
    }

    public static function show($my_model, $id){
        try{
            $record = $my_model::where('id', $id)->first();
            if(empty($record)){
                return [false, 'record not found'];
            }
        }catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while fetching record'];
        }
        return [true, $record];
    }

    public static function list($my_model){
        try{
            $records = $my_model::all();
        }catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while fetching records'];
        }
        return [true, $records];
    }
        
}
