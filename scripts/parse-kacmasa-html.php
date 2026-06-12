<?php

foreach ([1, 2] as $page) {
    $file = __DIR__."/kacmasa-page-{$page}.html";
    if (! is_file($file)) {
        continue;
    }
    $html = file_get_contents($file);
    preg_match_all('#href="(https://kacmasa\.com/[^"]+)" onclick="" class="product-img#', $html, $urls);
    preg_match_all('#class="product-img[^"]*"[^>]*>.*?alt="([^"]+)"#s', $html, $alts);
    echo "PAGE {$page}\n";
    foreach ($urls[1] as $i => $url) {
        $name = $alts[1][$i] ?? '';
        echo ($i + 1).". {$name}\n   {$url}\n";
    }
}
