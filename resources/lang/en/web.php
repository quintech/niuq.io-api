<?php

return [
    'api' => [
        'server' => [
            'error' => '伺服器異常'
        ],
        'eth'     => [
            'erc-20' => [
                'TransferSaveSuccessful'       => '交易ERC20成功，請確認您的交易ID並稍後幾分鐘!',
                'TransferSuccessfulSaveError'  => '交易ERC20成功，請確認您的交易ID並稍後幾分鐘,資料庫寫入失敗！',
                'TransferWrongReason'          => [
                    'gas'             => '因系統帳戶不組以支付手續費終止交易!',
                    'trans'           => '系統錯誤交易失敗!',
                    'account'         => '您的交易帳戶有錯誤，請重新檢測後再次交易!',
                    'accountLength'   => '您的交易帳戶編號長度有錯誤請重新檢查後再次交易!',
                    'accountNotErc20' => '您的交易帳戶不能再Erc20交易!',
                ],
                'getTransferHistorySuccessful' => '取得使用者歷史交易紀錄成功！',
            ],
        ],
        'system'  => [
            'getIp'  => '取得IP成功',
            'import' => [
                'successful'                => '匯入成功！',
                'api_media_bias_fact_check' => '匯入第二隻API成功！',
                'ad_fontes_media'           => '匯入第一隻API成功',
            ],
        ],
        'news'    => [
            'getHtml' => [
                'Successful' => '取得網址的標籤成功!',
                'error'      => '取得失敗請確認後再次發送',
            ],
            'save'    => [
                'news' => '新增新聞成功!',
            ],
            'getNews' => [
                'Successful' => '透過UUID取得新聞成功!',
            ],
            'getApi'  => [
                'NODATA_UUID'           => '取得API資料成功!',
                'ad_Fontes_Media'       => '取得第一個API成功!',
                'media_bias_fact_check' => '取得第二個API成功!',
                'fact_mata'             => '取得第三個API成功!',
            ]
        ],
        'account' => [
            'save' => '新增帳號成功!',
            'edit' => [
                'success' => '編輯帳號成功!',
                'fail' => '編輯帳號失敗!',
            ],
            'get'  => '取得帳號資料成功!',
            'register' => [
                'success' => 'register success',
                'fail' => 'register fail',
            ]
        ],
    ]
];
