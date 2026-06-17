<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GuideController extends Controller
{
    /** @var array<string, array{title: string, excerpt: string, body: string}> */
    private const GUIDES = [
        'rc-oyuncak-secimi' => [
            'title' => 'Çocuklar için RC oyuncak seçimi',
            'excerpt' => 'Yaş, ölçek ve kullanım alanına göre doğru RC modeli nasıl seçilir?',
            'body' => 'RC oyuncaklarda ölçek (1:14, 1:16, 1:24), şarj süresi ve kullanım alanı (iç/dış mekân) kritik kriterlerdir. İlk kez alacaklar için orta ölçekli, dayanıklı gövde ve net kullanım kılavuzu olan modeller önerilir. INWELT kataloğundaki RC ürünlerini inceleyip satın almayı Kacmasa üzerinden tamamlayabilirsiniz.',
        ],
        'akilli-cihaz-rehberi' => [
            'title' => 'Akıllı cihaz alırken dikkat edilecekler',
            'excerpt' => 'Uyumluluk, pil ömrü ve uygulama desteği için kısa kontrol listesi.',
            'body' => 'Akıllı takip cihazları ve benzeri ürünlerde iOS/Android uyumluluğu, pil ömrü ve veri gizliliği politikası mutlaka kontrol edilmelidir. INWELT’te teknik özellikleri karşılaştırın; satın alma için Kacmasa bağlantısını kullanın.',
        ],
        'hediye-fikirleri' => [
            'title' => 'INWELT hediye fikirleri',
            'excerpt' => 'Zeka oyunlarından müzik setlerine pratik hediye önerileri.',
            'body' => 'Hediye seçerken yaş grubu ve kullanım amacını netleştirin. Eğitici zeka oyunları, taşınabilir müzik setleri ve kişisel bakım cihazları farklı hedef kitlelere hitap eder. Katalogda “Hediye Fikirleri” filtresini kullanarak hızlıca listeleyebilirsiniz.',
        ],
    ];

    public function index(): View
    {
        return view('pages.guides.index', ['guides' => self::GUIDES]);
    }

    public function show(string $slug): View
    {
        abort_unless(isset(self::GUIDES[$slug]), 404);

        return view('pages.guides.show', [
            'slug' => $slug,
            'guide' => self::GUIDES[$slug],
        ]);
    }
}
