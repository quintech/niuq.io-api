<?php

namespace App\Services\Api\News;

use App\Http\Requests\News\NewRequest;
use App\Models\News;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsService extends RootService
{
    /**
     * Get all HTML tags of this news
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getUrlData(Request $request) : JsonResponse
    {
        try{
            $url = $request->query('url');
            // Connect to the URL
            $curl_init = curl_init($url);
            // Set connection options
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, 1);
            $html = curl_exec($curl_init);
            curl_close($curl_init);
            return response()->json(['url'     => $url,
                                     'message' => trans('web.api.news.getHtml.Successful'),
                                     'htmlTag' => $html
            ]);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    /**
     * Save news
     * @param  NewRequest  $request
     * @return JsonResponse
     */
    public function saveNews(NewRequest $request) : JsonResponse
    {
        $title                      = $request->get('title');
        $url                        = $request->get('url');
        $ad_fontes_media_uuid       = $request->get('ad_fontes_media_uuid');
        $media_bias_fact_check_uuid = $request->get('media_bias_fact_check_uuid');
        $fact_mata_context          = $request->get('fact_mata_context');
        $reliability                = $request->get('reliability');
        $factual_reporting          = $request->get('factual_reporting');
        $fakeness                   = $request->get('fakeness');
        $author                     = $request->get('author');
        $description                = $request->get('description');
        $addDate                    = $request->get('addDate');
        $image                      = $request->get('image');
        $from                       = $request->get('from');

        // Check if the news has been added before
        $countCheck = News::get()->where('url', $url)->first();

        if ($countCheck != null) {
            $count = $countCheck->count + 1;
            $reliability       = 0;
            $factual_reporting = 0;
            $fakeness          = 0;
        } else {
            $count             = 0;
            $reliability       = 0;
            $factual_reporting = 0;
            $fakeness          = 0;
        }
        DB::beginTransaction();
        try {
            News::updateOrCreate([
                'url' => $url
            ], [
                'title'                      => $title,
                'count'                      => $count,
                'ad_fontes_media_uuid'       => $ad_fontes_media_uuid,
                'media_bias_fact_check_uuid' => $media_bias_fact_check_uuid,
                'fact_mata_context'          => $fact_mata_context,
                'reliability'                =>  $reliability,        //0823
                'factual_reporting'          =>  $factual_reporting,        //0823
                'fakeness'                   =>  $fakeness,       //0823
                'author'                     =>  $author,       //0823
                'description'                =>  $description,       //0823
                'addDate'                    =>  $addDate,       //0823
                'image'                      =>  $image,       //0823
                'from'                       =>  $from,       //0823
            ]);
            DB::commit();
            $newData = News::where('url', '=', $url)->first();
            return response()->json(["message" => trans('web.api.news.save.news'), 'newUUID' => $newData->uuid], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "message"      => trans('databaseError.api.news.saveError'),
                'errorMessage' => $e->getMessage()
            ], 500);
        }
    }

}
