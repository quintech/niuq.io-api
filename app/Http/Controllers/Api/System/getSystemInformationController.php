<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use App\Services\Api\System\getSystemInformationService;
use Illuminate\Http\JsonResponse;

class getSystemInformationController extends Controller
{

    private $getSystemInformationService;

    public function __construct(
        getSystemInformationService $getSystemInformationService
    ) {
        $this->getSystemInformationService = $getSystemInformationService;
    }

    public function getIp() : JsonResponse
    {
        return $this->getSystemInformationService->getIp();
    }

}
