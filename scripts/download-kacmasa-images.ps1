$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
$base = Join-Path $PSScriptRoot '..\storage\app\public\products'

$products = @(
    @{
        slug = 'akilli-robot-kopek'
        url = 'https://kacmasa.com/uzaktan-kumandali-sarjli-cok-fonksiyonlu-egitici-ve-ogretici-interaktif-akilli-robot-kopekcin-versiyonu6501557'
        match = 'robot-kopek|akilli-robot'
    },
    @{
        slug = 'kart-tipi-gps-takip-cihazi'
        url = 'https://kacmasa.com/kart-tipi-gps-takip-cihazi-gercek-zamanli-konum-takibi-su-gecirmez-android-ios-uyumlu'
        match = 'kart-tipi-gps|gps-takip'
    },
    @{
        slug = 'elektrikli-tirnak-kesici-beyaz'
        url = 'https://kacmasa.com/sarjli-otomatik-elektrikli-tirnak-kesici-ve-torpuleyici-2-kademeli-hiz-ayarli-bebek-ve-yetiskinler-icin-guvenli-tirnak-bakim-cihazi-beyaz'
        match = 'tirnak-kesici|tirnak-bakim'
    },
    @{
        slug = 'roll-up-piyano-49-tus'
        url = 'https://kacmasa.com/49-tuslu-katlanabilir-silikon-roll-up-elektronik-piyano-tasinabilir-esnek-klavye'
        match = 'rollup-elektronik-piyano|roll-up-elektronik-piyano|49-tuslu'
    },
    @{
        slug = 'rc-mini-ekskavator-oyuncak'
        url = 'https://kacmasa.com/uzaktan-kumandali-mini-ekskavator-oyuncagi-164-olcekli-sarjli-rc-is-makinesi-stem-egitici-oyuncak-programlama-ogrenme-araci-erkek-cocuk-hediyesi'
        match = 'mini-ekskavator|1781010814'
    },
    @{
        slug = 'bluetooth-ceviri-gozlugu'
        url = 'https://kacmasa.com/akilli-bluetooth-ceviri-gozlugu-144-dil-destegi-mikrofonlu-ve-hoparlorlu'
        match = 'ceviri-gozlugu|bluetooth-ceviri'
    },
    @{
        slug = 'usb-c-hizli-sarj-adaptoru'
        url = 'https://kacmasa.com/akilli-usb-c-hizli-sarj-adaptoru-ai-otomatik-sarj-kesme-teknolojili-guvenli-guc-kesme-ozelligi-yumusak-silikon-korumali-seyahat-dostu-hizli-sarj-cihazi'
        match = 'usbc-hizli-sarj|usb-c-hizli-sarj|sarj-adaptoru'
    }
)

function Get-BestGalleryImages {
    param([string]$Html, [string]$MatchPattern)

    $all = [regex]::Matches($Html, 'image/cache/wkseller/711/([^"\s]+\.(?:webp|jpg|jpeg|png))') |
        ForEach-Object { $_.Groups[1].Value } |
        Where-Object { $_ -match $MatchPattern } |
        Select-Object -Unique

    $picked = @{}
    foreach ($path in $all) {
        if ($path -notmatch '_(\d+)-(?:\d{10,}-)?\d+x\d+') { continue }
        $idx = [int]$Matches[1]
        $score = 0
        if ($path -match '1200x1800') { $score = 100 }
        elseif ($path -match '900x1350') { $score = 90 }
        elseif ($path -match '600x900') { $score = 70 }
        else { continue }

        if (-not $picked.ContainsKey($idx) -or $score -gt $picked[$idx].Score) {
            $picked[$idx] = @{ Path = $path; Score = $score }
        }
    }

    return ($picked.GetEnumerator() | Sort-Object Name | ForEach-Object { $_.Value.Path })
}

foreach ($product in $products) {
    $dir = Join-Path $base $product.slug
    if ((Get-ChildItem -Path $dir -Filter 'g*.webp' -ErrorAction SilentlyContinue | Measure-Object).Count -gt 0) {
        Write-Host "Skipping $($product.slug) (already downloaded)"
        continue
    }

    New-Item -ItemType Directory -Force -Path $dir | Out-Null

    Write-Host "Downloading $($product.slug)..."
    $response = Invoke-WebRequest -Uri $product.url -TimeoutSec 45 -UseBasicParsing
    $images = Get-BestGalleryImages -Html $response.Content -MatchPattern $product.match

    if ($images.Count -eq 0) {
        throw "No images found for $($product.slug)"
    }

    $i = 1
    foreach ($imgPath in $images) {
        $url = "https://kacmasa.com/image/cache/wkseller/711/$imgPath"
        $out = Join-Path $dir ("g{0}.webp" -f $i)
        Invoke-WebRequest -Uri $url -OutFile $out -TimeoutSec 60 -UseBasicParsing
        $i++
    }

    Write-Host "  -> $($images.Count) images"
}

Write-Host 'Done.'
