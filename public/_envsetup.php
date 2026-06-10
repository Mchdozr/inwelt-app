<?php
// One-shot: clear bootstrap/cache compiled files then self-delete. Remove after use.
if (($_GET['token'] ?? '') !== 'inw-7Kq2Mp') {
    http_response_code(403);
    exit('forbidden');
}

header('Content-Type: text/plain; charset=utf-8');

$base = realpath(__DIR__ . '/..');
$cacheDir = $base . '/bootstrap/cache';
$deleted = [];
foreach (glob($cacheDir . '/*.php') as $f) {
    if (@unlink($f)) {
        $deleted[] = basename($f);
    }
}
echo "cache_dir: {$cacheDir}\n";
echo "deleted: " . (empty($deleted) ? '(none)' : implode(', ', $deleted)) . "\n";

$envPath = $base . '/.env';
echo "env_exists: " . (file_exists($envPath) ? 'yes' : 'no') . "\n";
echo "env_size: " . (file_exists($envPath) ? filesize($envPath) : 0) . "\n";

@unlink(__FILE__);
echo "self_deleted: " . (file_exists(__FILE__) ? 'no' : 'yes') . "\n";
