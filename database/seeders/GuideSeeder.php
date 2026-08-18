<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::updateOrCreate(
            ['email' => 'editor@inwelt.com.tr'],
            [
                'name' => 'INWELT Editör',
                'slug' => 'inwelt-editor',
                'bio' => 'INWELT ürün ve alışveriş içeriklerini teknik özellikler ve pazaryeri verilerine göre derleyen editör.',
                'expertise' => ['akıllı cihazlar', 'RC oyuncak', 'hediye'],
                'password' => Hash::make(env('ADMIN_PASSWORD', 'inwelt2026')),
            ]
        );

        $categories = Category::query()->pluck('id', 'slug');

        foreach ($this->guides() as $item) {
            Guide::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'body' => $item['body'],
                    'category_id' => $categories[$item['category']] ?? null,
                    'author_id' => $author->id,
                    'seo_title' => $item['seo_title'] ?? Str::limit($item['title'], 70, ''),
                    'seo_description' => $item['excerpt'],
                    'faq_items' => $item['faq'],
                    'is_active' => true,
                    'published_at' => now()->subDays(2),
                ]
            );
        }

        $links = [
            'dijital-davul-seti-9-pedli' => ['dijital-davul-seti-alirken', 'hediye-fikirleri'],
            'smart-tag-takip-cihazi' => ['smart-tag-nasil-calisir', 'akilli-cihaz-rehberi'],
            'i17-pro-mini-akilli-telefon' => ['mini-akilli-telefon-karsilastirma', 'akilli-cihaz-rehberi'],
            'j1-max-akilli-sohbet-hoparloru' => ['akilli-hoparlor-vs-bluetooth'],
            'drift-car-bluetooth-scooter' => ['drift-scooter-alirken'],
        ];

        foreach ($links as $productSlug => $guideSlugs) {
            $product = Product::query()->where('slug', $productSlug)->first();

            if ($product) {
                $product->related_guide_slugs = $guideSlugs;
                $product->save();
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function guides(): array
    {
        return [
            $this->driftScooterGuide(),
            $this->smartSpeakerGuide(),
            $this->make(
                'rc-oyuncak-secimi',
                'Çocuklar için RC oyuncak seçimi: 2026 komple rehber',
                'Yaş, ölçek ve güvenlik kriterleriyle doğru RC modeli nasıl seçilir?',
                'rc-oyuncak',
                ['Ölçek nedir?', 'Hangi yaş için hangi RC?', 'Güvenlik', 'Bakım', 'INWELT önerileri']
            ),
            $this->make(
                'akilli-cihaz-rehberi',
                'Akıllı cihaz alırken dikkat edilecekler 2026',
                'Uyumluluk, pil ömrü ve uygulama desteği için kısa kontrol listesi.',
                'akilli-cihazlar',
                ['iOS ve Android uyumu', 'Pil ve bağlantı', 'Gizlilik', 'Mini telefon', 'Smart Tag']
            ),
            $this->make(
                'hediye-fikirleri',
                'INWELT hediye fikirleri 2026',
                'Zeka oyunlarından müzik setlerine yaşa göre pratik hediye önerileri.',
                'zeka-egitici',
                ['Çocuk hediyeleri', 'Hobi hediyeleri', 'Teknoloji', 'Bütçe', 'Teslimat']
            ),
            $this->make(
                'dijital-davul-seti-alirken',
                'Dijital davul seti alırken dikkat edilecekler',
                'Pad sayısı, pedal, kulaklık çıkışı ve taşınabilirlik kriterleri.',
                'muzik-eglence',
                ['Pad ve pedal', 'Bağlantılar', 'Yeni başlayanlar', 'Hediye olarak davul', 'INWELT modelleri']
            ),
            $this->make(
                'smart-tag-nasil-calisir',
                'Akıllı takip etiketi (Smart Tag) nasıl çalışır?',
                'Find My ve Google Find Hub ile eşya takibinin pratik anlatımı.',
                'akilli-cihazlar',
                ['Bluetooth menzili', 'Pil', 'Uyum', 'Kullanım alanları', 'Satın alma']
            ),
            $this->make(
                'mini-akilli-telefon-karsilastirma',
                'Mini akıllı telefon vs standart telefon karşılaştırması',
                'Cebe sığan ekran, depolama ve çift SIM farkları.',
                'akilli-cihazlar',
                ['Ekran boyutu', 'Depolama', 'IMEI notu', 'Kimler için?', 'Karşılaştırma']
            ),
            $this->make(
                'elektrikli-tirnak-bakim-rehberi',
                'Elektrikli tırnak bakım cihazları rehberi',
                'Evde manikür için hız, uç seti ve güvenlik ipuçları.',
                'kisisel-bakim',
                ['Uç çeşitleri', 'Güvenlik', 'Bakım', 'Kimler kullanır', 'INWELT seçenekleri']
            ),
            $this->make(
                'cocuklar-icin-zeka-oyunlari',
                'Çocuklar için eğitici zeka oyunları',
                'Tangram, manyetik blok ve yaşa uygun zeka oyunu seçimi.',
                'zeka-egitici',
                ['Yaş grupları', 'Motor beceri', 'Odak', 'Hediye', 'Katalog']
            ),
            $this->make(
                'rc-araba-olcek-karsilastirma',
                'RC araba 1:14 vs 1:16 vs 1:24: hangi ölçek?',
                'Ölçek, iç/dış mekân ve ilk alım için net karşılaştırma tablosu.',
                'rc-oyuncak',
                ['1:14', '1:16', '1:24', 'Zemin', 'Karar tablosu']
            ),
            $this->make(
                'bluetooth-takip-ios-android',
                'Bluetooth takip cihazı iOS vs Android',
                'Find My ve Find Hub farkları, menzil ve pil karşılaştırması.',
                'akilli-cihazlar',
                ['Apple Find My', 'Google Find Hub', 'Çift uyum', 'Pil', 'Sonuç']
            ),
            $this->make(
                'kacmasa-inwelt-satin-alma',
                'Kacmasa’dan INWELT ürünleri nasıl satın alınır',
                'Vitrinden pazaryerine adım adım satın alma rehberi.',
                'akilli-cihazlar',
                ['Vitrin', 'Fiyat aralığı', 'Kacmasa', 'Trendyol ve HB', 'Destek']
            ),
            $this->make(
                'inwelt-garanti-iade',
                'INWELT ürün garantisi ve iade süreci',
                'Garanti ve iade, satın alınan pazaryerinin kurallarına göre işler.',
                'guvenlik-outdoor',
                ['Garanti', 'İade', 'Kargo', 'İletişim', 'Belgeler']
            ),
            $this->make(
                '2026-hediye-trendleri',
                '2026 popüler hediye trendleri',
                'Taşınabilir müzik, akıllı takip ve eğitici oyun trendleri.',
                'zeka-egitici',
                ['Trend 1', 'Trend 2', 'Trend 3', 'Bütçe', 'INWELT listesi']
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function driftScooterGuide(): array
    {
        $body = '<p><strong>Kısa cevap:</strong> Drift scooter alırken motor gücü, yaş ve kilo limiti, fren tipi, zemin ve batarya menzilini birlikte okuyun. INWELT Drift Car 350W fırçasız motor, kampana fren, 70 kg ve 8 yaş+ ile bu kriterleri net yazar.</p>'
            .'<h2 id="motor-ve-hiz">Motor ve hız kademesi</h2>'
            .'<p>350W fırçasız motor düz zeminde drift için yeterli tork üretir. Üç kademe yeni başlayanı yavaşlatır. Üretici hızı 15 km/sa civarındadır; gerçek hız sürücü ağırlığına göre düşer. İlk turda en düşük kademeyi kullanın.</p>'
            .'<h2 id="yas-kilo-guvenlik">Yaş, kilo ve güvenlik</h2>'
            .'<p>8 yaş ve üzeri, tek kişi, maksimum 70 kg. Kask, dizlik ve dirseklik şarttır. Trafik, ıslak zemin ve rampa yasaktır. Ebeveyn gözetimi olmadan hediye etmeyin.</p>'
            .'<h2 id="fren-tekerlek">Fren ve tekerlek</h2>'
            .'<p>Kampana fren duruş mesafesini kısaltır. Ön 200x50, arkada PU LED drift tekerlekleri kaymayı görünür kılar. Lastik aşınmasını düzenli kontrol edin.</p>'
            .'<h2 id="batarya">Batarya ve menzil</h2>'
            .'<p>36V 4.4 Ah lityum, şarj 2-4 saat, menzil yaklaşık 10 km. Tamamen bitirmeden şarj edin. Kılavuzdaki voltaj uyarısına uyun.</p>'
            .'<h2 id="satin-alma">Satın alma</h2>'
            .'<p>Ürün sayfası: <a href="/urun/drift-car-bluetooth-scooter">INWELT Drift Car</a>. En düşük fiyat vitrinde, ödeme Kacmasa satıcı sayfasındadır.</p>';

        return [
            'slug' => 'drift-scooter-alirken',
            'title' => 'Drift scooter alırken nelere bakılır? 2026 rehber',
            'excerpt' => 'Motor watt, yaş limiti, fren, LED tekerlek ve batarya menziliyle doğru elektrikli drift scooter seçimi.',
            'seo_title' => 'Drift scooter alırken nelere bakılır | INWELT',
            'category' => 'rc-oyuncak',
            'body' => $body,
            'faq' => [
                ['question' => 'Hangi yaşta drift scooter kullanılır?', 'answer' => 'INWELT Drift Car 8 yaş ve üzeri, tek kişi, en fazla 70 kg için tasarlanmıştır.'],
                ['question' => '350W yeterli mi?', 'answer' => 'Düz zemin drift için evet. Yokuş ve ağır sürücüde hız düşer. 3 kademe ile alışın.'],
                ['question' => 'Nerede sürülebilir?', 'answer' => 'Düz, kuru, açık alan. Trafik ve ıslak zemin uygun değildir.'],
                ['question' => 'Nereden alınır?', 'answer' => 'INWELT vitrininden ürünü açıp Kacmasa bağlantısıyla satın alın.'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function smartSpeakerGuide(): array
    {
        $body = '<p><strong>Kısa cevap:</strong> Klasik Bluetooth hoparlör sadece müzik çalar. Akıllı sohbet hoparlörü ekran, asistan ve dokunmatik kontrol ekler. INWELT J1-MAX 2,01 inç ekran, 10W ses ve 2000 mAh batarya ile masaüstü asistan sınıfındadır.</p>'
            .'<h2 id="fark">Akıllı hoparlör vs klasik Bluetooth</h2>'
            .'<p>Klasik model telefona bağlanır ve şarkı çalar. Akıllı model sohbet, menü ve kısa komut için ekran kullanır. Parti sesi arıyorsanız watt yüksek klasik kutu seçin. Masaüstü asistan istiyorsanız J1-MAX sınıfına bakın.</p>'
            .'<h2 id="ekran-ses">Ekran ve ses</h2>'
            .'<p>2,01 inç dokunmatik ekran temel kontrolleri gösterir. 10W ve 57 mm sürücü oda konseri değil, net masaüstü sestir. 80 Hz-18 kHz günlük dinleme aralığıdır.</p>'
            .'<h2 id="pil">Pil ve menzil</h2>'
            .'<p>2000 mAh ile yüzde elli seste yaklaşık 5 saat. Type-C şarj yaklaşık 3 saat. Bluetooth alma mesafesi 3 metreye kadar; uzak oda için değildir.</p>'
            .'<h2 id="kullanim">Kimler için?</h2>'
            .'<p>Ev ofis, çalışma masası, yatak kenarı. Çocuk partisi veya stüdyo monitörü değildir. Hediye olarak teknoloji meraklısı yetişkine uygundur.</p>'
            .'<h2 id="satin-alma">Satın alma</h2>'
            .'<p>Ürün: <a href="/urun/j1-max-akilli-sohbet-hoparloru">J1-MAX akıllı sohbet hoparlörü</a>. En düşük fiyat vitrinde, ödeme Kacmasa’dadır.</p>';

        return [
            'slug' => 'akilli-hoparlor-vs-bluetooth',
            'title' => 'Akıllı hoparlör mü klasik Bluetooth mu? 2026 karşılaştırma',
            'excerpt' => 'Ekran, sohbet, watt ve pil farkıyla akıllı sohbet hoparlörü ile klasik Bluetooth hoparlör karşılaştırması.',
            'seo_title' => 'Akıllı hoparlör vs Bluetooth hoparlör | INWELT',
            'category' => 'muzik-eglence',
            'body' => $body,
            'faq' => [
                ['question' => 'J1-MAX klasik hoparlörden farkı nedir?', 'answer' => 'Dokunmatik ekran ve yapay zekâ sohbeti ekler. Ses gücü 10W masaüstü kullanımı içindir.'],
                ['question' => 'Bluetooth menzili yeterli mi?', 'answer' => 'Yaklaşık 3 metre. Aynı oda ve masaüstü için uygundur, ev boyu kapsama vaat etmez.'],
                ['question' => 'Pil ne kadar gider?', 'answer' => '2000 mAh, yüzde elli seste yaklaşık 5 saat. Type-C ile yaklaşık 3 saatte dolar.'],
                ['question' => 'Nereden alınır?', 'answer' => 'INWELT ürün sayfasından Kacmasa satıcı bağlantısıyla satın alın.'],
            ],
        ];
    }

    /**
     * @param  list<string>  $headings
     * @return array<string, mixed>
     */
    private function make(string $slug, string $title, string $excerpt, string $category, array $headings): array
    {
        $pad = ' INWELT vitrininde ürünleri teknik tablo, kullanım senaryosu ve Kacmasa-Trendyol-Hepsiburada fiyat aralığıyla karşılaştırırsınız. Satın alma seçtiğiniz pazaryerinde tamamlanır; stok ve kargo satıcıya aittir. Karar verirken yaş, kullanım alanı, pil/şarj, güvenlik ve hediye amacını netleştirin. Bu rehber 2026 arama niyetine göre answer-first yazılmıştır.';

        $body = '<p><strong>Kısa cevap:</strong> '.$excerpt.' '.$pad.'</p>';
        $body .= '<table><thead><tr><th>Kriter</th><th>Ne bakmalısınız?</th><th>INWELT ipucu</th></tr></thead><tbody>';
        $body .= '<tr><td>Uyumluluk</td><td>iOS/Android, yaş, iç/dış mekân</td><td>Ürün spec tablosunu okuyun</td></tr>';
        $body .= '<tr><td>Fiyat</td><td>Üç satıcı aralığı</td><td>En güncel tutar pazaryerinde</td></tr>';
        $body .= '<tr><td>Teslimat</td><td>Kargo politikası</td><td>Satıcı sayfasını kontrol edin</td></tr>';
        $body .= '</tbody></table>';

        foreach ($headings as $heading) {
            $id = Str::slug($heading);
            $body .= '<h2 id="'.$id.'">'.$heading.'</h2>';
            $body .= '<p>'.$heading.' konusunda net kriterler kullanın.'.$pad.$pad.'</p>';
            $body .= '<ul><li>İhtiyacı tek cümleyle yazın.</li><li>Rakip alternatifleri aynı tabloda kıyaslayın.</li><li>INWELT ürün sayfasındaki teknik özellikleri kaydedin.</li><li>Pazaryeri yorumlarını ve iade kuralını okuyun.</li></ul>';
            $body .= '<p>Bu bölümü kapattıktan sonra ilgili kategoriye dönüp filtreleri kullanın.'.$pad.'</p>';
        }

        return [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'category' => $category,
            'body' => $body,
            'faq' => [
                ['question' => $title.' için ilk adım nedir?', 'answer' => 'İhtiyacı netleştirip INWELT ürün sayfasındaki özellikleri ve fiyat aralığını karşılaştırın.'],
                ['question' => 'Fiyat kesin mi?', 'answer' => 'Hayır. Aralığı vitrinde görürsünüz; kesin tutar Kacmasa, Trendyol veya Hepsiburada’dadır.'],
                ['question' => 'Nereden almalıyım?', 'answer' => 'Kargo ve kampanyaya göre seçin. Ürün sayfasındaki üç satıcı bağlantısını kullanın.'],
                ['question' => 'Destek alabilir miyim?', 'answer' => 'Evet. İletişim formu veya WhatsApp üzerinden yönlendirme yapılır.'],
            ],
        ];
    }
}
