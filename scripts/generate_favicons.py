"""Generate favicon set from the official INWELT circular logo."""
from __future__ import annotations

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
PUBLIC = ROOT / 'public'
SOURCE = (
    Path.home()
    / '.cursor'
    / 'projects'
    / 'c-Users-kacma-OneDrive-Masa-st-Inwelt'
    / 'assets'
    / 'c__Users_kacma_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_inwelt-logo-google-39389f6b-825c-47ab-98bb-38b5331eaf91.png'
)

SIZES = {
    'favicon-16x16.png': 16,
    'favicon-32x32.png': 32,
    'favicon-48x48.png': 48,
    'apple-touch-icon.png': 180,
    'favicon-192x192.png': 192,
    'favicon-512x512.png': 512,
    'favicon.png': 192,
}


def remove_dark_background(image: Image.Image) -> Image.Image:
    rgba = image.convert('RGBA')
    pixels = rgba.load()
    width, height = rgba.size

    for y in range(height):
        for x in range(width):
            r, g, b, a = pixels[x, y]
            if a == 0:
                continue
            if max(r, g, b) <= 24:
                pixels[x, y] = (r, g, b, 0)

    return rgba


def fit_square(image: Image.Image, size: int, padding: float = 0.08) -> Image.Image:
    canvas = Image.new('RGBA', (size, size), (0, 0, 0, 0))
    inner = int(size * (1 - padding * 2))
    fitted = image.copy()
    fitted.thumbnail((inner, inner), Image.Resampling.LANCZOS)
    offset = ((size - fitted.width) // 2, (size - fitted.height) // 2)
    canvas.paste(fitted, offset, fitted)
    return canvas


def save_ico(square_images: dict[int, Image.Image], path: Path) -> None:
    images = [square_images[size].convert('RGBA') for size in (16, 32, 48)]
    images[0].save(path, format='ICO', sizes=[(img.width, img.height) for img in images], append_images=images[1:])


def main() -> None:
    if not SOURCE.exists():
        raise FileNotFoundError(SOURCE)

    logo = remove_dark_background(Image.open(SOURCE))
    rendered: dict[int, Image.Image] = {}

    for filename, size in SIZES.items():
        square = fit_square(logo, size)
        square.save(PUBLIC / filename, optimize=True)
        rendered[size] = square
        print(f'wrote {filename}')

    save_ico(rendered, PUBLIC / 'favicon.ico')
    print('wrote favicon.ico')


if __name__ == '__main__':
    main()
