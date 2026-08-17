<?php

use App\Support\SeoEnv;

$ga4 = env('GA4_MEASUREMENT_ID', 'G-8NHKMLFX3D');
$gtm = env('GTM_CONTAINER_ID');

return [
    'ga4_measurement_id' => (is_string($ga4) && preg_match('/^G-[A-Z0-9]+$/', $ga4) && ! str_contains($ga4, 'XXX'))
        ? $ga4
        : 'G-8NHKMLFX3D',
    'gtm_container_id' => (is_string($gtm) && preg_match('/^GTM-[A-Z0-9]+$/', $gtm) && ! str_contains($gtm, 'XXX'))
        ? $gtm
        : null,
    'google_site_verification' => SeoEnv::verification(env('GOOGLE_SITE_VERIFICATION')),
];
