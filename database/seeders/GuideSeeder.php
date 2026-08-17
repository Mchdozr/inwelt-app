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
                    'seo_title' => Str::limit($item['title'], 70, ''),
                    'seo_description' => $item['excerpt'],
                    'faq_items' => $item['faq'],
                    'is_active' => true,
                    'published_at' => now()->subDays(2),
            ]
        );

        $links = [
            'dijital-davul-seti-9-pedli' => ['dijital-davul-seti-alirken', 'hediye-fikirleri'],
            'smart-tag-takip-cihazi' => ['smart-tag-nasil-calisir', 'akilli-cihaz-rehberi'],
            'i17-pro-mini-akilli-telefon' => ['mini-akilli-telefon-karsilastirma', 'akilli-cihaz-rehberi'],
        ];

        foreach ($links as $productSlug => $guideSlugs) {
            $product = Product::query()->where('slug', $productSlug)->first();

            if ($product) {
                $product->related_guide_slugs = $guideSlugs;
                $product->save();
            }
        }
    }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function guides(): array
    {
        return [
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
