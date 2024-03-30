<?php

namespace App\Services\Excel;

use App\Imports\AdFontesMediaImport;
use App\Imports\MediaBiasFactCheckImport;
use App\Models\User;
use App\Services\RootService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelService extends RootService
{

    /**
     * Import Excel data. This method handles the import and returns the result. The first and second APIs call this method to complete the import.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function importApiData(Request $request) : JsonResponse
    {
        // Check the file name and format
        $fileStatus = $this->checkFile($request);

        try {
            switch ($fileStatus) {
                case 0:
                    return response()->json(['message' =>trans('databaseError.api.system.importError')]);
                case 1:
                    Excel::import(new AdFontesMediaImport(), $request->file('api_ad_fontes_media'));
                    return response()->json(['message' => trans('web.api.system.import.ad_fontes_media')]);
                case 2:
                    Excel::import(new MediaBiasFactCheckImport(), $request->file('api_media_bias_fact_check'));
                    return response()->json(['message' =>trans('web.api.system.import.api_media_bias_fact_check')]);
                case 3:
                    return response()->json(['message' =>trans('databaseError.api.system.importPermissionError')]);
                case 4:
                    return response()->json(['message' =>trans('databaseError.api.system.importFileError')]);
                default:
                    return response()->json(['message' =>trans('databaseError.api.system.importError')]);
            }
        }catch (Exception $exception){
            return response()->json(['message' => trans('databaseError.api.system.importError'),'Error' => $exception->getMessage()]);
        }
    }

    /**
     * Check which API needs to be imported.
     * Return values: 0 = error, no such API, 1 = ad_fontes_media, 2 = media_bias_fact_check, 3 = You are not a system administrator!, 4 = Incorrect file name or file corruption.
     * @param  Request  $request
     * @return int
     */
    private function checkFile(Request $request) : int
    {
        if ($request->hasFile('api_ad_fontes_media')) {
            return $this->checkFileValid($request, 1);
        } elseif ($request->hasFile('api_media_bias_fact_check')) {
            return $this->checkFileValid($request, 2);
        } else {
            return 0;
        }
    }

    /**
     * Check if the file is valid.
     * Return values: 0 = error, no such API, 1 = ad_fontes_media, 2 = media_bias_fact_check, 3 = You are not a system administrator!, 4 = Incorrect file name or file corruption.
     * @param  Request  $request
     * @param  int  $num
     * @return int
     */
    private function checkFileValid(Request $request, int $num) : int
    {
        if ($num == 1) {
            if ($request->file('api_ad_fontes_media')->isValid()) {
                return $this->checkFileFormat($request, 1);
            } else {
                // Return 4 for error
                return 4;
            }
        }
        if ($num == 2) {
            if ($request->file('api_media_bias_fact_check')->isValid()) {
                return $this->checkFileFormat($request, 2);
            } else {
                // Return 4 for error
                return 4;
            }
        } else {
            // Return 0 for error
            return 0;
        }
    }

    /**
     * Check if the file format is correct.
     * Return values: 0 = error, no such API, 1 = ad_fontes_media, 2 = media_bias_fact_check, 3 = You are not a system administrator!, 4 = Incorrect file name or file corruption.
     * @param  Request  $request
     * @param  int  $num
     * @return int
     */
    private function checkFileFormat(Request $request, int $num) : int
    {
        if ($num == 1) {
            $file     = $request->file('api_ad_fontes_media');
            $fileName = str_replace('.xlsx', '', $file->getClientOriginalName());
            if ($file->getClientOriginalExtension() == "xlsx") {
                if ($this->checkFileName($fileName)) {
                    // File name is correct
                    return 1;
                } else {
                    // Return 4 for incorrect file name
                    return 4;
                }
            } else {
                // File format is not XLSX
                return 4;
            }
        } elseif ($num == 2) {
            $file     = $request->file('api_media_bias_fact_check');
            $fileName = str_replace('.xlsx', '', $file->getClientOriginalName());
            if ($file->getClientOriginalExtension() == "xlsx") {
                if ($this->checkFileName($fileName)) {
                    // File name is correct
                    return 2;
                } else {
                    // Return 4 for incorrect file name
                    return 4;
                }
            } else {
                // File format is not XLSX
                return 4;
            }
        } else {
            // Return 0 for error
            return 0;
        }
    }

    /**
     * Check if the file name is correct and valid; here we check the user UUID in the User table of the database.
     * Return values: false = incorrect user, true = correct user
     * @param $fileName
     * @return bool
     */
    private function checkFileName($fileName) : bool
    {
        $userCheck = User::where('uuid', '=', str_replace('.xlsx', '', $fileName))->get();
        if ($userCheck == null) {
            return false;
        } else {
            return true;
        }
    }
}
