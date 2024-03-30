<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewRequest;
use App\Services\Api\News\GetNewsService;
use App\Services\Api\News\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $NewsService;

    public function __construct(
        NewsService $NewsService
    ) {
        $this->NewsService = $NewsService;
    }

    /**
    * Retrieve all HTML tags of the current URL
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getUrlData(Request $request) : JsonResponse
    {
        return $this->NewsService->getUrlData($request);
    }

    /**
    * Add news
     * @param  NewRequest  $request
     * @return JsonResponse
     */
    public function saveNews(NewRequest $request) : JsonResponse
    {
        return $this->NewsService->saveNews($request);
    }

}
