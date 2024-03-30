<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdFontesMedia;

class CreateAdFontesMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        if (AdFontesMedia::where('source','=','NODATA')->first() == null){
            AdFontesMedia::create([
                'source'     => 'NODATA',
                'domain_url'    => 'NODATA',
                'bias'    => 'NODATA',
                'reliability'    => 'NODATA',
                'bias_label'    => 'NODATA',
                'reliability_label'    => 'NODATA',
                'media_type'    => 'NODATA',
            ]);
        }
    }
}
