<?php

namespace App\Services\Api\News;

use App\Models\MediaBiasFactCheckJson;
use App\Models\MediaBiasFactCheck;
use App\Models\News;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetMediaBiasFactCheckService extends RootService
{
    /**
     * Get data from MediaBiasFactCheck
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getMediaBiasFactCheckData(Request $request) : JsonResponse
    {
        $newUUID   = $request->query('uuid');
        $dbAllData = News::where('uuid', '=', $newUUID)->with('getMediaBiasFactCheck')->get();

        if ($newUUID == null) {
            return response()->json(['message' => trans('databaseError.api.news.uuidError'),]);
        }

        // Avoiding issues with missing UUID
        try {
            if ($dbAllData[0]->getMediaBiasFactCheck != null) {
                // if ($dbAllData[0]->getMediaBiasFactCheck->url == 'NODATA') {
                    // Get all data from the database
                    $apiDataJson = MediaBiasFactCheckJson::all();
                    $apiData = MediaBiasFactCheck::all();

                    // Automatically filter the frontend URL
                    $hostName = $this->AutoRelpaseName($dbAllData[0]->url);
                    // Automatically filter data that matches the frontend domain
                    $suitableData = $this->GetSuitableApi($apiData, $hostName);

                    $suitableDataJson = $this->GetSuitableApiJson($apiDataJson, $hostName);

                    // Write to the database
                    $dbAllData = $this->commitDataBase($dbAllData[0], $suitableData,$suitableDataJson);
                    
                // }
                // $abstract = $this->findAbstract($dbAllData[0]->getMediaBiasFactCheck);
                return response()->json([
                    'success'           => true,
                    'message'           => trans('web.api.news.getApi.media_bias_fact_check'),
                    'ApiData'           => $dbAllData[0],
                    'abstract'          => $suitableDataJson != 0 ? $suitableDataJson[0] : 0,
                    'factual_reporting' => $dbAllData[0]->factual_reporting,
                    'suitableData'      => $suitableData,
                    'u'                 => $suitableDataJson != 0 ? $suitableDataJson[2] : 0,
                ]);

            } else {
                // If UUID is incorrect
                return response()->json([
                    'success' => false,
                    'message' => trans('databaseError.api.news.uuidError'),
                ]);
            }
        } catch (Exception $ex) {
            Log::error($ex->getFile() . ' ' . $ex->getLine() . ' ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => trans('databaseError.api.news.findNewsError'),
                'Error'   => $ex->getMessage()
            ]);
        }
    }

    /**
     * Automatically filter the URL (remove unnecessary parameters and http/https, return only the domain name)
     * @param $url
     * @return array|false|int|string|null
     */
    public function AutoRelpaseName($url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Automatically determine if the data in the database matches the news URL domain
     * @param $apiData
     * @param $hostName
     * @return array|bool|int|string
     */
    public function GetSuitableApiJson($apiData, $hostName)
    {
        // If there is an error in the frontend data, return 0 directly
        if ($hostName == null) {
            return 0;
        }
        foreach ($apiData as $data) {
            if (stristr($hostName, $data->name)) {
                // Perform summary filtering directly
                $MediaData[0] = $this->findAbstract($data);
                $MediaData[1] = $data->uuid;
                $MediaData[2] = $data->u;
                return $MediaData;
            }
        }
        // If no search results are found, return this value
        return 0;
    }

    public function GetSuitableApi($apiData, $hostName)
    {
        // If there is an error in the frontend data, return 0 directly
        if ($hostName == null) {
            return 0;
        }
        foreach ($apiData as $data) {
            if (stristr($hostName, $data->web_name)) {
                // Perform summary filtering directly
                $MediaData[0] = $this->findAbstract($data);
                $MediaData[1] = $data->uuid;
                return $MediaData;
            }
        }
        // If no search results are found, return this value
        return 0;
    }

    /**
     * Write to the database
     * @param $dbAllData
     * @param $suitableData
     */
    public function commitDataBase($dbAllData, $suitableData, $suitableDataJson)
    {
        // Check if it is 0, if it is 0, there is an error, no need to write to the database, just return the new search results
        try {
            if ($suitableData != 0 && $suitableData[1] !== 0) {
                $factual_reporting = $suitableDataJson != 0 ? $this->getScore($suitableDataJson[0]) : 0;
                DB::beginTransaction();
                try {
                    News::updateOrCreate([
                        'url' => $dbAllData->url
                    ], [
                        'title'                      => $dbAllData->title,
                        'count'                      => $dbAllData->count,
                        'ad_fontes_media_uuid'       => $dbAllData->ad_fontes_media_uuid,
                        'media_bias_fact_check_uuid' => $suitableData[1],
                        'fact_mata_context'          => $dbAllData->fact_mata_context,
                        'reliability'                => $dbAllData->reliability,
                        //0823
                        'factual_reporting'          => $factual_reporting,
                        //0823
                        'fakeness'                   => $dbAllData->fakeness,
                        //0823
                    ]);
                    DB::commit();
                } catch (Exception $ex) {
                    DB::rollBack();
                    Log::error($ex->getFile() . ' ' . $ex->getLine() . ' ' . $ex->getMessage());
                    return News::where('uuid', '=', $dbAllData->uuid)->with('getMediaBiasFactCheck')->get();
                }
            }
        } catch (Exception $ex) {
            Log::error($ex->getFile() . ' ' . $ex->getLine() . ' ' . $ex->getMessage());
        }

        return News::where('uuid', '=', $dbAllData->uuid)->with('getMediaBiasFactCheck')->get();
    }

    /**
     * Filter the data and find the abstract for frontend use
     */
    private function findAbstract($apiData)
    {

        $context = $apiData->r;
        if ($context != "NODATA") {
            if (strpos($context, "VH") !== false) {
                return "VERY HIGH";
            }
            if (strpos($context, "H") !== false) {
                return "HIGH";
            }
            if (strpos($context, "MF") !== false) {
                return "MOSTLY FACTUAL";
            }
            if (strpos($context, "M") !== false) {
                return "MIXED";
            }
            if (strpos($context, "L") !== false) {
                return "LOW";
            }
            if (strpos($context, "VL") !== false) {
                return "VERY LOW";
            }
        }
        return "NODATA";
    }

    /**
     * Return the score
     */
    private function getScore($str)
    {
        if ($str != "NODATA") {
            if (strpos($str, "VERY HIGH") !== false) {
                return 9;
            }
            if (strpos($str, "HIGH") !== false) {
                return 8;
            }
            if (strpos($str, "MOSTLY FACTUAL") !== false) {
                return 6.4;
            }
            if (strpos($str, "MIXED") !== false) {
                return 4.8;
            }
            if (strpos($str, "LOW") !== false) {
                return 3.2;
            }
            if (strpos($str, "VERY LOW") !== false) {
                return 1.6;
            }
        }
        return "NODATA";
    }
}
