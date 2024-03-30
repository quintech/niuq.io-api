<?php

namespace App\Services\Api\News;

use App\Models\AdFontesMedia;
use App\Models\News;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetAdFontesMediaService extends RootService
{
    /**
    * Get data from AdFontesMedia
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getAdFontesMedia(Request $request): JsonResponse
    {
        $newUUID   = $request->query('uuid');
        $dbAllData = News::where('uuid', '=', $newUUID)->with('getAdFontesMedia')->get();

        if ($newUUID == null) {
            return response()->json(['message' => trans('databaseError.api.news.uuidError'),]);
        }

        // Avoid encountering issues with missing UUID
        try {
            // Avoid server errors caused by incorrect UUID input
            if ($dbAllData[0]->getAdFontesMedia != null) {
                if ($dbAllData[0]->getAdFontesMedia->source == 'NODATA') {
                    // Get all data from the database
                    $apiData = AdFontesMedia::all();
                    // Automatically filter the frontend URL
                    $hostName = $this->AutoRelpaseName($dbAllData[0]->url);
                    // Automatically filter data that matches the frontend domain
                    $suitableData = $this->GetSuitableApi($apiData, $hostName);
                    // Write to the database
                    $dbAllData = $this->commitDatabase($dbAllData[0], $suitableData);
                }

                return response()->json(['message' => trans('web.api.news.getApi.ad_Fontes_Media'), 'ApiData' => $dbAllData[0]]);
            } else {
                // If UUID is incorrect
                return response()->json(['message' => trans('databaseError.api.news.uuidError'),]);
            }
        } catch (Exception $ex) {
            Log::error($ex->getFile() . ' ' . $ex->getLine() . ' ' . $ex->getMessage());
            return response()->json(['message' => trans('databaseError.api.news.findNewsError'), 'error' => $ex->getMessage()]);
        }
    }

    /**
    * Automatically determine whether the data in the database matches the domain of the news URL
     * @param $apiData
     * @param $hostName
     * @return array|bool|int|string
     */
    public function GetSuitableApi($apiData, $hostName)
    {
        // If there is an error in the frontend data validation, return 0 directly
        if ($hostName == null) {
            return 0;
        }
        foreach ($apiData as $data) {
            $domainUrl = $this->AutoRelpaseName($data->domain_url);
            if ($domainUrl != null) {
                // Check if a matching value is found
                if (stristr($domainUrl, $hostName) !== false) {
                    return $data;
                }
            }
        }
        // If no search results are found, this value will be returned
        return 0;
    }

    /**
    * Automatically filter URLs (remove unnecessary parameters, https, http, www, and return only the domain name)
     * @param $url
     * @return array|false|int|string|null
     */
    public function AutoRelpaseName($url)
    {
        $domain_url = parse_url($url, PHP_URL_HOST);
        if (str_starts_with($domain_url, "www.")) {
            return substr($domain_url, 4);
        } else {
            return parse_url($url, PHP_URL_HOST);
        }
    }

    /**
    * Write to the database
     */
    public function commitDatabase($dbAllData, $suitableData)
    {
        // Check if it is 0, if it is 0 it means there is an error, no need to write to the database, just return the fresh search result
        if ($suitableData != null) {
            $suitableDataJson = json_decode($suitableData);
            DB::beginTransaction();
            try {
                News::updateOrCreate([
                    'url' => $dbAllData->url
                ], [
                    'title'                      => $dbAllData->title,
                    'count'                      => $dbAllData->count,
                    'ad_fontes_media_uuid'       => $suitableDataJson->uuid,
                    'media_bias_fact_check_uuid' => $dbAllData->media_bias_fact_check_uuid,
                    'fact_mata_context'          => $dbAllData->fact_mata_context,
                    'reliability'                =>  $suitableDataJson->reliability,       //0823
                    'factual_reporting'          =>  $dbAllData->factual_reporting,
                    'fakeness'                   =>  $dbAllData->fakeness,       //0823
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return News::where('uuid', '=', $dbAllData->uuid)->with('getAdFontesMedia')->get();
            }
        }
        return News::where('uuid', '=', $dbAllData->uuid)->with('getAdFontesMedia')->get();
    }
}
