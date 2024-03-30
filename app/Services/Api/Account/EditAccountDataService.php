<?php

namespace App\Services\Api\Account;

use App\Models\GoogleUser;
use App\Models\User;
use App\Services\RootService;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class EditAccountDataService extends RootService
{
    /**
     * Edit user profile
     * @param  Request  $request
     * @return JsonResponse
     */
    public function userEditProfile(Request  $request): JsonResponse
    {
        $gender = $request->gender;
        // Filter gender
        if ($gender == "true"){
            $gender = true;
        }else{
            if ($gender == "false"){
                $gender = false;
            }else{
                $gender = null;
            }
        }

        DB::beginTransaction();
        try {
            if(auth('api')->check()){
                $uuid = auth('api')->user()->uuid;
                $user = User::where('uuid',$uuid)->update([
                    'wallet_account'    => $request->niuq_id,
                    'birthday'          => $request->birthday,
                    'gender'            => $gender,
                    'country'           => $request->country,
                    'state'             => $request->state,
                    'zip'               => $request->zip,
                    'address'           => $request->address
                ]);
            }else{
                GoogleUser::updateOrCreate([
                    'google_id' => $request->google_id
                ], [
                    'wallet_account'    => $request->niuq_id,
                    'birthday'   => $request->birthday,
                    'gender'     => $gender,
                    'country'    => $request->country,
                    'state'      => $request->state,
                    'zip'        => $request->zip,
                    'address'    => $request->address,
                ]);
            }
            DB::commit();
            return response()->json(['success'=>true,'message'=>trans('web.api.account.edit.success')]);
        }catch (Exception $e){
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>trans('web.api.account.edit.fail'),'Reason'=>$e->getMessage()]);
        }
    }
}
