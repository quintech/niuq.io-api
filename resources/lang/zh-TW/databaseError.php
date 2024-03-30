<?php

return [
    'api' => [
        'news'        => [
            'saveError'        => '新增新聞時發生問題!',
            'getError'         => '透過UUID取得新聞時發生問題!',
            'uuidError'        => '取得UUID錯誤!',
            'findNewsError'    => '伺服器找不到此新聞!',
            'findApiDataError' => '伺服器找不到相關Api資料!',
        ],
        'account'     => [
            'saveError' => '新增帳號時發生問題！',
            'getError'  => '搜尋不到此帳號！'
        ],
        'transaction' => [
            'getNull'  => '搜尋不到交易紀錄！',
            'getError' => '搜尋交易時發生錯誤！',
            'exist' => '您已分享過該新聞'
        ],
        'system'      => [
            'importError'           => '匯入失敗',
            'importPermissionError' => '您並不是系統管理員！',
            'importFileError'       => '檔案出現毀損或是錯誤！',
        ]
    ],
];
