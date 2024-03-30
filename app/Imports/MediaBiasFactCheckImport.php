<?php

namespace App\Imports;

use App\Models\MediaBiasFactCheck;
use App\Models\News;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;



class MediaBiasFactCheckImport implements OnEachRow,WithBatchInserts, WithChunkReading
{
        // Whether to perform API data re-import
        // True: Set all news as NODATA and delete other data before importing new data
        // False: Update duplicate data, keep old data
        public $allClear = false;

        // This function updates the data if it is duplicate, otherwise adds it to the database based on the web_name field
        public function onRow(Row $row)
        {
            $rowIndex = $row->getIndex();
            $row      = $row->toArray();

            // If importing from another API
    //        if ($row[0] != 'web_name'){
    //            echo "It seems like you selected the wrong file, this is not the data from the second API! \n";
    //        }

            // Avoid inserting the header
            if ($row[0] == 'web_name'){
                // Check if there is content when searching the first row
                if ($this->checkAllClear($row)) {
                    $this->allClear = true;
                } else {
                    $this->allClear = false;
                }
            }else{
                // Process from the second row onwards
                if ($this->allClear){
                    // Set the UUID of all news to NODATA to avoid errors when accessing the news
                    $this->setNewsNoData();
                    // Clear the database table and leave only NODATA
                    $this->clearDBData();
                    // Write the imported data
                    $this->saveData($row);
                    // This can only be entered once
                    $this->allClear = false;
                }else{
                    $this->saveData($row);
                }
            }
        }

        public function batchSize() : int
        {
            return 10;
        }

        public function chunkSize() : int
        {
            return 10;
        }

        // Check if all data needs to be cleared
        public function checkAllClear($row) : bool
        {
            try {
                if ($row[3] != null) {
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
                MediaBiasFactCheck::updateOrCreate([
                    'web_name' => $row[0],
                ],[
                    'web_name' => $row[0],
                    'url'      => $row[1],
                    'context'  => $row[2],
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
                $mediaBiasFactCheck      = MediaBiasFactCheck::where('web_name', '!=', 'NODATA')->get();

                foreach ($mediaBiasFactCheck as $media){
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
        $mediaBiasFactCheck     = MediaBiasFactCheck::where('web_name', '=', 'NODATA')->first();
        if ($mediaBiasFactCheck != null){
            $needEditNews = News::where('media_bias_fact_check_uuid','!=',$mediaBiasFactCheck->uuid)->get();

            foreach ($needEditNews as $item){
                DB::beginTransaction();
                try {
                    News::updateOrCreate([
                        'uuid' => $item->uuid,
                    ], [
                        'media_bias_fact_check_uuid' => $mediaBiasFactCheck->uuid,
                    ]);
                    DB::commit();
                }catch (Exception $e){
                    DB::rollBack();
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
