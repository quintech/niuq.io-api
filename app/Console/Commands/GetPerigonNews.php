<?php

namespace App\Console\Commands;

use App\Http\Requests\News\NewRequest;
use App\Services\Api\News\GetAdFontesMediaService;
use App\Services\Api\News\GetFactMataService;
use App\Services\Api\News\GetMediaBiasFactCheckService;
use App\Services\Api\News\GetNewsApiService;
use App\Services\Api\News\NewsService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetPerigonNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perigon:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得 Perigon News';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(
        GetMediaBiasFactCheckService  $GetMediaBiasFactCheckService,
        GetAdFontesMediaService  $GetAdFontesMediaService,
        GetFactMataService $GetFactMataService,
        GetNewsApiService $getNewsApiService,
        NewsService $newsService
    ) {

        $api = 'https://api.goperigon.com/v1/all?apiKey=' . config('app.goperigon_api_key') .'&showReprints=false&paywall=false&excludeLabel=Non-news&excludeLabel=Opinion&excludeLabel=Paid%20News&excludeLabel=Roundup&excludeLabel=Press%20Release&sortBy=date&size=15';
        $this->info('API URL: '.$api);
        $result = Http::withOptions([
            'verify' => false,
        ])->get($api);
        $data =   json_decode($result->body());

        try {
            if ($data->status == 200) {
                foreach ($data->articles as $index => $val) {
                    $noDataUUIDArr =  $getNewsApiService->getNoDataUUID();
                    $request = new NewRequest();
                    $request->replace([
                        'title' => $val->title,
                        'url' => $val->url,
                        'ad_fontes_media_uuid' => $noDataUUIDArr['adFontesMediaUUID'],
                        'media_bias_fact_check_uuid' => $noDataUUIDArr['mediaBiasFactCheckUUID'],
                        'fact_mata_context' => 'NODATA',
                        'author' => $val->authorsByline,
                        'description' => $val->description,
                        'addDate' => Date::parse($val->addDate)->format('Y-m-d H:i:s'),
                        'image' => $val->imageUrl,
                        'from' => 'perigon'
                    ]);
                    $uuid = $newsService->saveNews($request)->getData()->newUUID;
                    $request_uuid = new NewRequest();
                    $request_uuid->replace(['uuid' => $uuid]);

                    $adForntesMediaRes = $GetAdFontesMediaService->getAdFontesMedia($request_uuid);

                    $GetMediaBiasFactCheckService->getMediaBiasFactCheckData($request_uuid);

                    $GetFactMataService->getFactMata($request_uuid);
                }
            } else {
                Log::warning('Exception occurred in Perigon API: ' . $data->message);
                return 0;
            }
        } catch (Exception $ex) {
            Log::error($ex->getFile() . ' ' . $ex->getLine() . ' ' . $ex->getMessage());
        }
        Log::info(Date::now()->format('Y/m/d') . ' New addition completed');
        return 0;
    }
}
