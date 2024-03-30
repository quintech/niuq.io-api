<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Services\Excel\ImportExcelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MediaBiasFactCheckJson;



class ImportExcelController extends Controller
{

    private $importExcelService;

    public function __construct(
        ImportExcelService $importExcelService
    )
    {
        $this->importExcelService = $importExcelService;
    }

    /**
    * Get the import API data view
     */
    public function view(){
        return view('Import.index');
    }

    /**
    * Import API data
     * @param  Request  $request
     * @return JsonResponse
     */
    public function importApiData(Request $request){
        return  $this->importExcelService->importApiData($request);
    }

    public function importJson(Request $request){
        $json = file_get_contents('https://raw.githubusercontent.com/drmikecrowe/mbfcext/400580d3ce13f3881dea6e8d60961149ba44c74e/docs/v4/csources-pretty.json'); ;
        $obj = json_decode($json, true);
        
        foreach ($obj as $key => $values){
            
            DB::beginTransaction();
            try{
                MediaBiasFactCheckJson::UpdateorCreate(
                 [
                    'name'  => $key,
                    'b'     => isset($values['b']) ? $values['b'] : null,
                    'd'     => isset($values['d']) ? $values['d'] : null,
                    'f'     => isset($values['f']) ? $values['f'] : null,
                    'n'     => isset($values['n']) ? $values['n'] : null,
                    'r'     => isset($values['r']) ? $values['r'] : null,
                    'u'     => isset($values['u']) ? $values['u'] : null,
                    'p'     => isset($values['p']) ? $values['p'] : null,
                    'c'     => isset($values['c']) ? $values['c'] : null,
                    'a'     => isset($values['a']) ? $values['a'] : null,
                ]);
                DB::commit();

            }catch(Exception $e){
                DB::rollBack();
            }
        }
        // return var_dump($obj);
        
    } 
}
