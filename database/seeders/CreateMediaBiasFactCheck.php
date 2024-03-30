<?php

namespace Database\Seeders;

use App\Models\MediaBiasFactCheck;
use Illuminate\Database\Seeder;

class CreateMediaBiasFactCheck extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (MediaBiasFactCheck::where('web_name', '=', 'NODATA')->first() == null) {
            //預設NODATA
            MediaBiasFactCheck::create([
                'web_name' => 'NODATA',
                'url'      => 'NODATA',
                'context'  => 'NODATA',
            ]);
        }
    }
}
