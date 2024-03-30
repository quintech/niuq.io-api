<?php

namespace App\Services\Api\Eth\Erc20;

use App\Models\GoogleUser;
use App\Models\Transaction;
use App\Services\RootService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetTrasactionService extends RootService
{
    /**
     * Get all transaction information for a specific user
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getUserTransaction(Request $request) : JsonResponse
    {
        if(auth('api')->check()){
            $transactionData = auth('api')->user()->transaction()->get();
            return response()->json(['success'=>true,'message' => trans('web.api.eth.erc-20.getTransferHistorySuccessful'),'transactionData' => $transactionData]);
        }


        // Keep the functionality code for Google authentication, to be merged with the user database in the future.
        // del: Merge Google into the user database in the future.
        $query = $request->query('google_id');
        if ($query === null) {
            return response()->json(['qu' => "NULL"]);
        } else {
            $userData = GoogleUser::where('google_id','=',$query)->first();
            // Check if the user exists
            if ($userData === null){
                // User not found
                return response()->json(['success'=>true,'message' => trans('databaseError.api.account.getError')]);
            }else{
                // Start searching if the user has any transactions
                $transactionData = Transaction::where('google_user_uuid', '=', $userData->uuid)->with('getGoogleUserData')->get();
                if ($transactionData === null){
                    return response()->json(['success'=>true,'message' => trans('databaseError.api.transaction.getNull')]);
                }else{
                    return response()->json(['success'=>true,'message' => trans('web.api.eth.erc-20.getTransferHistorySuccessful'),'transactionData' => $transactionData]);
                }
            }
        }
    }
}
