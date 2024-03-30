<?php

namespace App\Services\Api\Account;

use App\Http\Requests\System\CreateAccountRequest;
use App\Models\GoogleUser;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use DB;

class CreateAccountService extends RootService
{
    /**
     * Add Google account data to the database
     * @param  CreateAccountRequest  $request
     * @return JsonResponse
     */
    public function createGoogleAccount(CreateAccountRequest $request) : JsonResponse
    {
        //        return response()->json([
        //            'message' => $request->gender,
        //            'All' => $request->all(),
        //        ]);

        $wallet_account  = $this->checkValue($request->wallet_account);
        $birthday = $this->checkValue($request->birthday);
        $gender   = $this->checkValue($request->gender);
        $country  = $this->checkValue($request->country);
        $state    = $this->checkValue($request->state);
        $address  = $this->checkValue($request->address);
        $zip      = $this->checkValue($request->zip);

        // If the birthday is today, consider it as not selected
        if ($birthday == date('Y-m-d')) {
            $birthday = null;
        }

        // Filter gender
        if ($gender == "true") {
            $gender = true;
        } else {
            if ($gender == "false") {
                $gender = false;
            } else {
                $gender = null;
            }
        }

        DB::beginTransaction();
        try {
            GoogleUser::updateOrCreate([
                'google_id' => $request->google_id
            ], [
                'owner_name'   => $request->owner_name,
                'user_img_url' => $request->user_img_url,
                'email'        => $request->email,
                'google_id'    => $request->google_id,

                'wallet_account'    => $wallet_account,
                'birthday'   => $birthday,
                'gender'     => $gender,
                'country'    => $country,
                'state'      => $state,
                'zip'        => $zip,
                'address'    => $address,
                'registered' => true,
            ]);
            DB::commit();
            return response()->json([
                'message' => trans('web.api.account.save'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => trans('databaseError.api.account.saveError'),
                'log'     => $e
            ]);
        }
    }

    private function checkValue($value)
    {
        if ($value != null) {
            return $value;
        } else {
            return null;
        }
    }
}
