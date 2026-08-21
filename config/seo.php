<?php

use App\Support\SeoEnv;

return [
    'indexnow_key' => SeoEnv::indexNowKey(env('INDEXNOW_KEY')) ?? SeoEnv::INDEXNOW_DEFAULT,
    'bing_site_verification' => SeoEnv::verification(env('BING_SITE_VERIFICATION')),
    'founding_date' => '2024',
    'same_as' => [
        'instagram' => 'https://www.instagram.com/inwelt.com.tr/',
        'kacmasa' => 'https://www.kacmasa.com/magaza/Inwelt',
        'trendyol' => 'https://www.trendyol.com/magaza/inweltcom-m-1273830',
        'hepsiburada' => 'https://www.hepsiburada.com/magaza/inweltcom',
    ],
];
