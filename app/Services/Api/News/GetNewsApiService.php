<?php

namespace App\Services\Api\News;

use App\Models\AdFontesMedia;
use App\Models\MediaBiasFactCheck;
use App\Services\RootService;
use Illuminate\Http\JsonResponse;

class GetNewsApiService extends RootService
{

    /**
    * Get the UUID of the default no data status
     * @return JsonResponse
     */    
    public function process(){    
        $noDataUUIDArray = $this->getNoDataUUID();
        return response()->json([
            'message'                => trans('web.api.news.getApi.NODATA_UUID'),
            'AdFontesMediaUUID'      => $noDataUUIDArray['adFontesMediaUUID'],
            'MediaBiasFactCheckUUID' => $noDataUUIDArray['mediaBiasFactCheckUUID']
        ]);
    }
    
    /**
    * Get the UUID of the default no data status
     * @return array
     */
    public function getNoDataUUID(){
        $adFontesMedia      = AdFontesMedia::where('source', '=', 'NODATA')->first();
        $mediaBiasFactCheck = MediaBiasFactCheck::where('web_name', '=', 'NODATA')->first();

        if ($adFontesMedia != null) {
            $adFontesMediaUUID = $adFontesMedia->uuid;
        }
        if ($mediaBiasFactCheck != null) {
            $mediaBiasFactCheckUUID = $mediaBiasFactCheck->uuid;
        }

        return [
            'adFontesMediaUUID' => $adFontesMediaUUID,
            'mediaBiasFactCheckUUID' => $mediaBiasFactCheckUUID,
        ];
    }
}
