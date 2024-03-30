<?php

namespace App\Services\Api\Eth\Erc20;

use App\Http\Requests\Share\ShareNewRequest;
use App\Models\GoogleUser;
use App\Models\News;
use App\Models\Transaction;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\RequestException;

use Illuminate\Support\Facades\DB;

class Erc20TransactionService extends RootService
{
    /**
     * Receive transaction request from the frontend
     * @param  ShareNewRequest  $shareNewRequest
     * @return JsonResponse
     */
    public function getTransaction(ShareNewRequest $shareNewRequest) : JsonResponse
    {
        // Check if user is authenticated        
        if(auth('api')->check()){
            $user = auth('api')->user()->first();
            $user->wallet_account;
            $new_uuid = News::query()->where('url',$user->shareNewsURL)->first()->uuid;
            if($this->checkNewIsShared($user->uuid,$new_uuid)){
                return response()
                ->json(['message' => trans('databaseError.api.transaction.exist')]);
            }
            return $this->beginTransaction($user,$shareNewRequest);   
        // Check if user is authenticated with Google
        }else{
            $userData = GoogleUser::where('google_id', '=', $shareNewRequest->userGoogleID)->first();
            
            // Check if user exists
            if (is_null($userData)) {
                return response()
                ->json(['message' => trans('databaseError.api.account.getError')]);
            }
    
            // Verify if frontend data matches the database
            $status = $this->checkData($shareNewRequest, $userData);
            if(!$status){
                return response()->json([
                    'message' => trans('databaseError.api.account.getError'),
                    'status'  => $status ? 1 : 0
                ]);
            }
            
            // Check if the transaction account is a valid account
            if (!$this->checkAccount($userData->wallet_account)) {
                return response()
                ->json(['message' => trans('web.api.eth.erc-20.TransferWrongReason.account')]);
            }
    
            // Check if the news has already been shared
            $new_uuid = News::query()->where('url',$shareNewRequest->shareNewsURL)->first()->uuid;
            if($this->checkNewIsShared($userData->uuid,$new_uuid)){
                return response()
                ->json(['message' => trans('databaseError.api.transaction.exist')]);
            }
    
            // Begin transaction
            return $this->beginTransaction($userData,$shareNewRequest);    
        }
    }

    /**
    * Begin transaction
     * @param $token
     * @param $from_private
     * @param $from_address
     * @param $userData
     * @return JsonResponse
     */
    private function beginTransaction($userData,ShareNewRequest $shareNewRequest) : JsonResponse
    {

        try {
    
            $response = Http::get('https://api.etherscan.io/api?module=gastracker&action=gasoracle');
            $lowGasPrice = 0;
            //get low price
            if($response->ok() && $response['status']){
                $lowGasPrice = $response['result']['SafeGasPrice'] * (10**9);
            }else{
                throw new Exception($response['result']);
            }

            $process = new Process([
                'node', 
                resource_path() . '/js/' . 'connetTest.js',
                '--address',
                $userData->wallet_account,
                '--data',
                $shareNewRequest->shareNewsURL,
                '--gasPrice',
                $lowGasPrice
            ]);

            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new Exception($process->getErrorOutput());
            }

            $res = json_decode($process->getOutput(),True);

            if($res['success']){
                return $this->saveTransaction($res['hash'], $userData,$shareNewRequest);
            }else{
                return response()->json([
                    'success'        => false,
                    'message'        => trans('web.api.eth.erc-20.TransferWrongReason.trans'),
                    'err'            => $res
                ],500);
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getLine());
            Log::error($exception->getFile());
            return response()->json(['success' => false,'message' => $exception->getMessage()],500);
        }
    }

    /**
    * Write transaction information to the database
     * @param $transfer_tx_id
     * @param $userData
     * @return JsonResponse
     */
    private function saveTransaction($transfer_tx_id, $userData,ShareNewRequest $shareNewRequest) : JsonResponse
    {
        $new_uuid = News::query()->where('url',$shareNewRequest->shareNewsURL)->first()->uuid;

        DB::beginTransaction();
        try {
            if(auth('api')->check()){
                Transaction::updateOrCreate([
                    'user_uuid'    => $userData->uuid,
                    'news_uuid'    => $new_uuid,
                    'transaction_hex'     => $transfer_tx_id,
                    'niuq_erc20_quantity' => 0.01,
                ], [
                    'user_uuid'    => $userData->uuid,
                    'news_uuid'    => $new_uuid,
                    'transaction_hex'     => $transfer_tx_id,
                    'niuq_erc20_quantity' => 0.01,
                ]);
            }else{
                Transaction::updateOrCreate([
                    'google_user_uuid'    => $userData->uuid,
                    'news_uuid'    => $new_uuid,
                    'transaction_hex'     => $transfer_tx_id,
                    'niuq_erc20_quantity' => 0.01,
                ], [
                    'google_user_uuid'    => $userData->uuid,
                    'news_uuid'    => $new_uuid,
                    'transaction_hex'     => $transfer_tx_id,
                    'niuq_erc20_quantity' => 0.01,
                ]);
            }
            DB::commit();
            return response()->json([
                'success'        => true,
                'message'        => trans('web.api.eth.erc-20.TransferSaveSuccessful'),
                'transfer_tx_id' => $transfer_tx_id
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json([
                'success'        => false,
                'message'        => trans('web.api.eth.erc-20.TransferSuccessfulSaveError'),
                'transfer_tx_id' => $transfer_tx_id
            ]);
        }
    }

    /**
    * Verify if the frontend data matches the data in the database
     * @param $shareNewRequest
     * @param $userData
     * @return bool
     */
    private function checkData($shareNewRequest, $userData)
    {
        if (
            $shareNewRequest->userGoogleID !== $userData->google_id ||
            $shareNewRequest->userEmail !== $userData->email ||
            $shareNewRequest->userImg !== $userData->user_img_url ||
            $shareNewRequest->userName !== $userData->owner_name
            ) {
            return false;
        }
        // Verify Token
        // Verify if the timestamp is valid

        return true;
    }

    /**
     * Check if the transaction account is a valid account
     * @param $account
     * @return bool
     */
    private function checkAccount($account) : bool
    {
        // Split each letter of the account into a new array
        $check = preg_split('//', $account, -1, PREG_SPLIT_NO_EMPTY);

        // Check if the transmitted data has the correct account length
        if (count($check) != 42) {
            return false;
        }

        // Check if the first character of the account is '0'
        if ($check[0] != '0') {
            return false;
        }

        // Check if the second character of the account is 'x'
        if ($check[1] != 'x') {
            return false;
        }

        return true;
    }

    /**
     * Check if the news has already been shared
     * @param $google_uuid
     * @param $new_uuid
     * @return bool
     */
    private function checkNewIsShared($google_uuid,$new_uuid):bool
    {
        if(auth('api')->check()){
            return Transaction::query()
                ->where('user_uuid',$google_uuid)
                ->where('news_uuid',$new_uuid)
                ->exists();
        }else{
            return Transaction::query()
            ->where('google_user_uuid',$google_uuid)
            ->where('news_uuid',$new_uuid)
            ->exists();
        }
    }

    /**
     * Get the current lowest gas price on the ETH blockchain
     * @return number
     */
    private function getLowGas()
    {

        $response = Http::get('https://api.etherscan.io/api?module=gastracker&action=gasoracle');

        try{
            if($response->ok()){
                
                return $response['result']['SafeGasPrice'];
            }else{
                $response->throw();
            }
        }catch(RequestException $e){
            return response()->json(['success' => false,'message' => $e->getMessage()],500);
        }
    }
}
