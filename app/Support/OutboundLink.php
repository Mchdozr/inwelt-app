<?php

namespace App\Support;

final class OutboundLink
{
    public static function withUtm(string $url, string $campaign, ?string $content = null): string
    {
        $params = array_filter([
            'utm_source' => 'inwelt',
            'utm_medium' => 'referral',
            'utm_campaign' => $campaign,
            'utm_content' => $content,
        ]);

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.http_build_query($params);
    }
}
