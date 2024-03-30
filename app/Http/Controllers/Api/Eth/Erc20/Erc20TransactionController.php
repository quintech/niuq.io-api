<?php

namespace App\Http\Controllers\Api\Eth\Erc20;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\ShareNewRequest;
use App\Services\Api\Eth\Erc20\Erc20TransactionService;
use App\Services\Api\Eth\Erc20\GetTrasactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Erc20TransactionController extends Controller
{
    private $erc20TransactionService;
    private $getTrasactionService;
    public function __construct(
        Erc20TransactionService $erc20TransactionService,
        GetTrasactionService  $getTrasactionService
    ) {
        $this->erc20TransactionService = $erc20TransactionService;
        $this->getTrasactionService = $getTrasactionService;
    }

    /**
    * Give Niuq tokens to the user
     * @param  ShareNewRequest  $shareNewRequest
     * @return JsonResponse
     */
    public function giveUserNiuq(ShareNewRequest  $shareNewRequest) : JsonResponse
    {
        return $this->erc20TransactionService->getTransaction($shareNewRequest);
    }

    /**
    * Get transaction records
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getUserTrasaction(Request $request)
    {
        return $this->getTrasactionService->getUserTrasaction($request);
    }
}
