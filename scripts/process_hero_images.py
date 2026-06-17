"""Copy user hero assets and remove light/dark studio backgrounds."""
from __future__ import annotations

import shutil
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
ASSETS = Path.home() / '.cursor' / 'projects' / 'c-Users-kacma-OneDrive-Masa-st-Inwelt' / 'assets'
HERO_DIR = ROOT / 'public' / 'images' / 'hero'

USER_IMAGES = {
    'c__Users_kacma_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_inwelt-logo-google-26a4ddf7-eb78-4c5d-9a98-d909a289771c.png': 'hero-inwelt-logo.png',
    'c__Users_kacma_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_DRONE-9e947f19-f98f-45bb-90cd-4d3815a08415.png': 'hero-drone.png',
}


def luminance(r: int, g: int, b: int) -> float:
    return 0.2126 * r + 0.7152 * g + 0.0722 * b


def saturation(r: int, g: int, b: int) -> float:
    mx, mn = max(r, g, b), min(r, g, b)
    if mx == 0:
        return 0.0
    return (mx - mn) / mx


def background_alpha(r: int, g: int, b: int) -> int:
    lum = luminance(r, g, b)
    sat = saturation(r, g, b)

    if lum <= 18 and sat <= 0.2:
        return 0

    if lum <= 40 and sat <= 0.15:
        fade = lum / 40
        return max(0, min(255, int(255 * fade)))

    if lum >= 248 and sat <= 0.12:
        return 0

    if lum >= 235 and sat <= 0.08:
        return 0

    if lum >= 220 and sat <= 0.06 and b >= r and b >= g:
        return max(0, int((235 - lum) * 18))

    if lum >= 210 and sat <= 0.1:
        fade = (lum - 210) / 35
        return max(0, min(255, int(255 * (1 - fade))))

    return 255


def process_image(path: Path) -> None:
    image = Image.open(path).convert('RGBA')
    pixels = image.load()
    width, height = image.size

    for y in range(height):
        for x in range(width):
            r, g, b, a = pixels[x, y]
            if a == 0:
                continue
            bg_alpha = background_alpha(r, g, b)
            if bg_alpha < 255:
                pixels[x, y] = (r, g, b, min(a, bg_alpha))

    image.save(path, optimize=True)
    print(f'processed {path.name}')


def main() -> None:
    HERO_DIR.mkdir(parents=True, exist_ok=True)

    for source_name, target_name in USER_IMAGES.items():
        source = ASSETS / source_name
        if not source.exists():
            raise FileNotFoundError(source)
        target = HERO_DIR / target_name
        shutil.copy2(source, target)
        process_image(target)


if __name__ == '__main__':
    main()
