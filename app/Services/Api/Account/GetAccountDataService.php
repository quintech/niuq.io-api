<?php

namespace App\Services\Api\Account;

use App\Models\GoogleUser;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAccountDataService extends RootService
{

    /**
    * Get user data
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getGoogleAccountData(Request $request) : JsonResponse
    {
        try{
            if(auth('api')->check()){
                $userData = auth('api')->user();
                return response()->json(['success' => true,'message' => trans('web.api.account.get'), 'data' => $userData]);
            }
            $google_id     = $request->query('google_id');
            $googleAccount = GoogleUser::where('google_id', '=', $google_id)->first();
    
            if ($googleAccount == null){
                return response()->json(['success' => true,'message' => trans('web.api.account.get'), 'data' => "NODATA"]);
            }else{
                return response()->json(['success' => true,'message' => trans('web.api.account.get'), 'data' => $googleAccount]);
            }
        }catch(Exception $e){
            return response()->json(['success' => false,'message' => trans('web.api.server.error'), 'data' => $googleAccount]);
        }
    }
}
