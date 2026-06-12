<?php

declare(strict_types=1);

$source = $argv[1];
$dest = $argv[2] ?? dirname(__DIR__) . '/public/images/trendyol-logo.png';

$image = @imagecreatefromjpeg($source) ?: @imagecreatefrompng($source);
if ($image === false) {
    fwrite(STDERR, "Görsel okunamadı\n");
    exit(1);
}

$width = imagesx($image);
$height = imagesy($image);

$png = imagecreatetruecolor($width, $height);
imagealphablending($png, false);
imagesavealpha($png, true);

$transparent = imagecolorallocatealpha($png, 0, 0, 0, 127);
imagefilledrectangle($png, 0, 0, $width, $height, $transparent);

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        if ($r >= 235 && $g >= 235 && $b >= 235) {
            continue;
        }

        $color = imagecolorallocatealpha($png, $r, $g, $b, 0);
        imagesetpixel($png, $x, $y, $color);
    }
}

imagepng($png, $dest, 9);
imagedestroy($image);
imagedestroy($png);

echo "Kaydedildi: {$dest}\n";
