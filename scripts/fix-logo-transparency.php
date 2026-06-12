<?php

declare(strict_types=1);

$source = $argv[1] ?? dirname(__DIR__) . '/public/images/inwelt-logo.png';
$dest = $argv[2] ?? $source;

$jpeg = @imagecreatefromjpeg($source);
if ($jpeg === false) {
    $jpeg = @imagecreatefrompng($source);
}

if ($jpeg === false) {
    fwrite(STDERR, "Görsel okunamadı: {$source}\n");
    exit(1);
}

$width = imagesx($jpeg);
$height = imagesy($jpeg);

$png = imagecreatetruecolor($width, $height);
imagealphablending($png, false);
imagesavealpha($png, true);

$transparent = imagecolorallocatealpha($png, 0, 0, 0, 127);
imagefilledrectangle($png, 0, 0, $width, $height, $transparent);

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($jpeg, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // JPEG siyah arka planını şeffaf yap; koyu lacivert logo metni korunur
        if ($r <= 20 && $g <= 20 && $b <= 20) {
            continue;
        }

        $color = imagecolorallocatealpha($png, $r, $g, $b, 0);
        imagesetpixel($png, $x, $y, $color);
    }
}

imagepng($png, $dest, 9);
imagedestroy($jpeg);
imagedestroy($png);

echo "PNG kaydedildi: {$dest}\n";
