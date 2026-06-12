<?php

declare(strict_types=1);

$source = $argv[1];
$dest = $argv[2] ?? dirname(__DIR__) . '/public/images/kacmasa-logo.png';

$image = imagecreatefrompng($source);
if ($image === false) {
    fwrite(STDERR, "PNG okunamadı\n");
    exit(1);
}

$width = imagesx($image);
$height = imagesy($image);

imagealphablending($image, false);
imagesavealpha($image, true);

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $rgba = imagecolorat($image, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if ($r <= 15 && $g <= 15 && $b <= 15) {
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagesetpixel($image, $x, $y, $transparent);
        }
    }
}

imagepng($image, $dest, 9);
imagedestroy($image);

echo "Kaydedildi: {$dest}\n";
