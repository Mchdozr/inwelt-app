<?php

namespace App\Support;

final class OutboundLink
{
    public static function withUtm(string $url, string $campaign, ?string $content = null): string
    {
        $params = array_filter([
            'utm_source' => 'inwelt',
            'utm_medium' => 'marketplace_referral',
            'utm_campaign' => $content ?: $campaign,
            'utm_content' => $campaign,
        ]);

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.http_build_query($params);
    }
}
