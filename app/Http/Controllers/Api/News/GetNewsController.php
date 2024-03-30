<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use App\Services\Api\News\GetNewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetNewsController extends Controller
{

    private $GetNewsService;

    public function __construct(
        GetNewsService $GetNewsService
    ) {
        $this->GetNewsService = $GetNewsService;
    }

    /**
     * Retrieve all news
     * @return string
     */
    public function getAllNews() : string
    {
        return $this->GetNewsService->getAllNews();
    }

    /**
     * Retrieve the most popular news
     * @return string
     */
    public function getMostPopularNews() : string
    {
        return $this->GetNewsService->getMostPopularNews();
    }

    /**
     * Retrieve news with specified ID
     * @param Request $request
     * @return JsonResponse
     */
    public function getNewsDetail(Request $request) : JsonResponse
    {
        return $this->GetNewsService->getNews($request);
    }

    /**
     * Retrieve perigon news data
     * @return JsonResponse
     */
    public function getLatestNews()
    {
        return $this->GetNewsService->getLatestNews();
    }
}
