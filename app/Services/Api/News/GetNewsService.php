<?php

namespace App\Services\Api\News;

use App\Models\News;
use App\Services\RootService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetNewsService extends RootService
{
    /**
    * Retrieve all news (without sorting by popularity)
     * @return string
     */
    public function getAllNews() : string
    {
        return News::orderBy('created_at', 'desc')->get()->toJson();
    }

    /**
    * Retrieve popular news
     * @return string
     */
    public function getMostPopularNews() : string
    {
        return News::orderBy('count', 'desc')->get()->toJson();
    }

    /**
    * Retrieve a single news
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getNew(Request $request) : JsonResponse
    {
        $id = $request->query('newid');

        $new = News::get()->where('uuid', $id)->first();

        if ($new != null) {
            return response()->json([
                'new' => $new,
                'message' => trans('web.api.news.getNews.Successful'),
                ], 200);

        } else {
            return response()->json(["ERROR"   => trans('databaseError.api.news.getError'),
                                     'message' => 'UUID is not correct !'
            ], 500);
        }
    }

    /**
    * Get perigon news data
     * 
     */
    public function getLatestNews(){
        return response()->json([
           'data' => News::query()->where('from','perigon')->take(15)->get()->toArray(),
           'success' => true
        ]);
    }
}
