<?php

namespace App\Imports;

use App\Models\AdFontesMedia;
use App\Models\News;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class AdFontesMediaImport implements OnEachRow, WithBatchInserts, WithChunkReading
{
        // Whether to perform API data re-import
        // True: Set all news as NODATA and delete other data before importing new data
        // False: Updated duplicate data, old data will be retained
        public $allClear = false;

        public function onRow(Row $row)
        {
            $rowIndex = $row->getIndex();
            $row      = $row->toArray();

            // If importing from another API
    //        if ($row[0] != 'source'){
    //            echo "$row[0] It seems that you have selected the wrong file, this is not the data of the first API! \n";
    //        }

            // Avoid inserting the title
            if ($row[0] == 'source') {
                // Check if there is content when searching the first row
                if ($this->checkAllClear($row)) {
                    $this->allClear = true;
                } else {
                    $this->allClear = false;
                }
            } else {
                // Process from the second row onwards
                if ($this->allClear){
                    // Set the first API of all news to NODATA's UUID to avoid errors when clicking on the news
                    $this->setNewsNoData();
                    // Clear the data table and leave only NODATA
                    $this->clearDBData();
                    // Write the imported data
                    $this->saveData($row);
                    // Can only enter here once
                    $this->allClear = false;
                }else{
                    $this->saveData($row);
                }
            }
        }

        public function batchSize() : int
        {
            return 20;
        }

        public function chunkSize() : int
        {
            return 20;
        }

        // Check if all data needs to be cleared
        public function checkAllClear($row) : bool
        {
            try {
                if ($row[7] != null) {
                    return true;
                }else{
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        // Start writing to the database
        public function saveData($row){
            DB::beginTransaction();
            try {
                AdFontesMedia::updateOrCreate([
                    'domain_url' => $row[1],
                ], [
                    'source'            => $row[0],
                    'domain_url'        => $row[1],
                    'bias'              => $row[2],
                    'reliability'       => $row[3],
                    'bias_label'        => $row[4],
                    'reliability_label' => $row[5],
                    'media_type'        => $row[6],
                ]);
                DB::commit();
            }catch (Exception $e){
                DB::rollBack();
                Log::error($e->getMessage());
                echo $e->getMessage();
            }
        }

        // Clear the database
        private function clearDBData(){
            try {
                $adFontesMedia      = AdFontesMedia::where('source', '!=', 'NODATA')->get();

                foreach ($adFontesMedia as $media){
                    DB::beginTransaction();
                    try {
                        $media->forceDelete();
                        DB::commit();
                    }catch (Exception $e){
                        DB::rollBack();
                        Log::error($e->getMessage());
                        echo $e->getMessage();
                    }
                }
            }catch (Exception $e){
                Log::error($e->getMessage());
                echo $e->getMessage();
            }
        }

        // Set the first API data of all news to NODATA
        private function setNewsNoData(){
            $adFontesMedia      = AdFontesMedia::where('source', '=', 'NODATA')->first();

            if ($adFontesMedia != null){
                $needEditNews = News::where('ad_fontes_media_uuid','!=',$adFontesMedia->uuid)->get();

                foreach ($needEditNews as $item){
                    DB::beginTransaction();
                    try {
                    News::updateOrCreate([
                        'uuid' => $item->uuid,
                    ], [
                        'ad_fontes_media_uuid' => $adFontesMedia->uuid,
                    ]);
                    DB::commit();
                }catch (Exception $e){
                    DB::rollBack();
                    Log::error($e->getMessage());
                    echo $e->getMessage();
                }
            }
        }
    }



    //    /**
    //    * @param array $row
    //    *
    //    * @return Model|null
    //    */
    //    public function model(array $row)
    //    {
    //        if ($row[0] == "source"){
    //            //什麼都不要做
    //            return null;
    //        }else{
    //            return new AdFontesMedia([
    //                'uuid'     => Str::uuid()->toString(),
    //                'source' => $row[0],
    //                'domain_url'  => $row[1],
    //                'bias'  => $row[2],
    //                'reliability'  => $row[3],
    //                'bias_label'  => $row[4],
    //                'reliability_label'  => $row[4],
    //                'media_type'  => $row[5],
    //            ]);
    //        }
    //    }


}
