<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class APICallsService 
{
    public static function makeAPICall($full_url, $headerArray= ['Content-Type: application/json']){
        try{
            $call = Http::withHeaders($headerArray)->withoutVerifying()->get($full_url);
            $response = json_decode($call->body(), true);
        }
        catch(\Exception $e){
            Log::error($e);
            return [false, 'error occured while making API call'];
        }

        return [true, $response];
    }
}
