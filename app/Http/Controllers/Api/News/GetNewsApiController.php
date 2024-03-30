<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use App\Services\Api\News\GetAdFontesMediaService;
use App\Services\Api\News\GetFactMataService;
use App\Services\Api\News\GetMediaBiasFactCheckService;
use App\Services\Api\News\GetNewsApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetNewsApiController extends Controller
{

    private $GetNewsApiService;
    private $GetAdFontesMediaService;
    private $GetMediaBiasFactCheckService;
    private $GetFactMataService;

    public function __construct(
        GetNewsApiService $GetNewsApiService,
        GetAdFontesMediaService  $GetAdFontesMediaService,
        GetMediaBiasFactCheckService  $GetMediaBiasFactCheckService,
        GetFactMataService $GetFactMataService
    ) {
        $this->GetNewsApiService = $GetNewsApiService;
        $this->GetAdFontesMediaService = $GetAdFontesMediaService;
        $this->GetMediaBiasFactCheckService = $GetMediaBiasFactCheckService;
        $this->GetFactMataService = $GetFactMataService;
    }

    /**
     * Get the UUID with no data by default
     * @return JsonResponse
     */
    public function getNoDataUUID(){
        return $this->GetNewsApiService->process();
    }

    /**
     * Get data from AdFontesMedia
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getAdFontesMedia(Request $request) : JsonResponse
    {
        return $this->GetAdFontesMediaService->getAdFontesMedia($request);
    }

    /**
     * Get data from MediaBiasFactCheck
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getMediaBiasFactCheck(Request $request) : JsonResponse{
        return $this->GetMediaBiasFactCheckService->getMediaBiasFactCheckData($request);
    }

    /**
     * Get data from FactMata
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getFactMata(Request $request) : JsonResponse {
        return $this->GetFactMataService->getFactMata($request);
    }
}
