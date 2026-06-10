<?php
// One-shot bootstrap: writes ../.env then self-deletes. Remove after use.
if (($_GET['token'] ?? '') !== 'inw-7Kq2Mp') {
    http_response_code(403);
    exit('forbidden');
}

$env = <<<'ENV'
APP_NAME=Inwelt
APP_ENV=production
APP_KEY=base64:xHXHLwkRhp683k8n7fXX8tnwbG9AuXHhh0D0LsQK4rA=
APP_DEBUG=false
APP_URL=https://inwelt.com.tr
APP_LOCALE=tr
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=tr_TR
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pazarus_inwelt
DB_USERNAME=pazarus_inwelt
DB_PASSWORD=Inwelt2026DbKx7mPq
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log
MAIL_FROM_ADDRESS="info@inwelt.com.tr"
MAIL_FROM_NAME="Inwelt"
VITE_APP_NAME="Inwelt"

ENV;

$target = realpath(__DIR__ . '/..') . '/.env';
$bytes = file_put_contents($target, $env);

header('Content-Type: text/plain; charset=utf-8');
echo "target: {$target}\n";
echo "written_bytes: " . var_export($bytes, true) . "\n";
echo "exists: " . (file_exists($target) ? 'yes' : 'no') . "\n";
echo "size_on_disk: " . filesize($target) . "\n";

@unlink(__FILE__);
echo "self_deleted: " . (file_exists(__FILE__) ? 'no' : 'yes') . "\n";
