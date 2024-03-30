<?php

namespace App\Services\Api\News;

use App\Models\News;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\String\b;

class GetFactMataService extends RootService
{
    /**
    * Get data from FactMata
     * @param Request $request
     * @return JsonResponse
     */
    public function getFactMata(Request $request): JsonResponse
    {
        $newUUID = $request->query('uuid');
        $newData = News::where('uuid', '=', $newUUID)->first();

        if ($newData != null) {
            if ($newData->fact_mata_context == 'NODATA') {
                $newData = $this->getData($newData);
                if ($newData != 'NODATA') {
                    $this->saveData($newData,$newUUID);
                    $newData = News::where('uuid', '=', $newUUID)->first();
                    return response()->json(['message' => trans('web.api.news.getApi.fact_mata'), 'ApiData' => $newData]);
                } else {
                    //If there is an error retrieving the API
                    $newData = News::where('uuid', '=', $newUUID)->first();
                    return response()->json(['message' => trans('web.api.news.getApi.fact_mata'), 'ApiData' => $newData]);
                }
            }
        } else {
            //If UUID is incorrect
            return response()->json(['message' => trans('databaseError.api.news.uuidError'),]);
        }

        // The data stored is in JSON format, so when retrieving it, it is also output in JSON format for easier data retrieval by the frontend.
        $Data = json_decode($newData->fact_mata_context, true);
        // var_dump(count($Data));
        return response()->json(['message' => trans('web.api.news.getApi.fact_mata'), 'ApiData' => $Data]);
    }

    private function getData($newData)
    {
        try {
            $url = $newData->url;
            // The third-party API requires the following headers
            // Set headers
            //'X-API-Key: fm_rjkydsxoydrnwkmfzazltfono'
            $header = array('Content-Type: application/json', 'Accept: application/json', 'X-API-Key: fm_ndlvwkxjmkxxyspy');
            // Put the URL in the body and encode it in JSON format
            $body = ["url" => $url];
            $data = json_encode($body);
            echo $data;
            // Start the request and get the response data
            $opts = array(
                'http' =>
                    array(
                        'method' => 'POST',
                        'header' => $header,
                        'content' => $data
                    ),
                // TODO: SSL verification should be enabled based on the production environment status 2021/09/01
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                )
            );

            $context = stream_context_create($opts);
            $resultData = file_get_contents('https://scoring.factmata.com/api/score/url', false, $context);
            Log::info('FactMata data', ['resultData' => $resultData]);
            // The retrieved data is not in complete JSON format, so it needs to be converted before returning
            return json_decode($resultData, true);
        } catch (Exception $e) {
            Log::error('FactMata api 錯誤',['msg'=>$e->getMessage()]);
            return 'NODATA';
        }

    }

    // Successfully retrieved API and wrote data to the database
    private function saveData($saveData,$uuid)
    {
        $newData = News::where('uuid', '=', $uuid)->first();
        $fakeness = $saveData['results'][17]["prediction"]['score'];    //0826

        DB::beginTransaction();
        try {
            News::updateOrCreate([
                'url' => $newData->url
            ], [
                'title'                      => $newData->title,
                'count'                      => $newData->count,
                'ad_fontes_media_uuid'       => $newData->ad_fontes_media_uuid,
                'media_bias_fact_check_uuid' => $newData->media_bias_fact_check_uuid,
                'fact_mata_context'          => $saveData,
                'reliability'                => $newData->reliability,  //0823
                'factual_reporting'          => $newData->factual_reporting,
                'fakeness'                   => floatval($fakeness),  //0826
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            //            $this->logError($e->getMessage());
            //            return response()->json([
            //                "message"      => trans('databaseError.api.save.apiSavaFailed'),
            //                'errorMessage' => $e->getMessage()
            //            ], 500);
        }
    }
}
