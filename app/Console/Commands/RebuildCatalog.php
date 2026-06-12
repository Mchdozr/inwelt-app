<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RebuildCatalog extends Command
{
    protected $signature = 'inwelt:rebuild-catalog';

    protected $description = 'INWELT katalogunu (kategoriler + tüketici ürünleri) sıfırdan kurar';

    public function handle(): int
    {
        $this->info('Eski katalog temizleniyor...');

        DB::transaction(function () {
            DB::table('product_specs')->delete();
            DB::table('product_images')->delete();
            DB::table('use_cases')->delete();
            Product::query()->delete();
            Category::query()->delete();
        });

        $categories = $this->categories();
        $categoryIds = [];
        $sort = 0;

        foreach ($categories as $slug => $data) {
            $category = Category::create([
                'name' => $data['name'],
                'slug' => $slug,
                'icon' => $data['icon'],
                'description' => $data['description'],
                'sort' => $sort++,
                'is_active' => true,
            ]);
            $categoryIds[$slug] = $category->id;
        }

        $this->info(count($categoryIds).' kategori oluşturuldu.');

        $pSort = 0;

        foreach ($this->products() as $item) {
            $category = $categoryIds[$item['category']];

            $product = Product::create([
                'category_id' => $category,
                'name' => $item['name'],
                'slug' => $item['slug'],
                'badge' => $item['badge'] ?? null,
                'tags' => $item['tags'] ?? [],
                'summary' => $item['summary'],
                'description' => $item['description'],
                'cover_image' => null,
                'seller_url' => $item['seller_url'] ?? null,
                'is_featured' => $item['featured'] ?? false,
                'is_advantageous' => $item['advantageous'] ?? false,
                'is_active' => true,
                'sort' => $pSort++,
                'seo_title' => $item['name'].' | INWELT',
                'seo_description' => Str::limit(strip_tags($item['summary']), 155),
            ]);

            [$cover, $gallery] = $this->resolveImages($item['slug']);
            $product->cover_image = $cover;
            $product->save();

            foreach ($gallery as $i => $path) {
                $product->images()->create([
                    'path' => $path,
                    'alt' => $item['name'],
                    'sort' => $i,
                ]);
            }

            foreach (array_values($item['specs']) as $i => [$label, $value]) {
                $product->specs()->create(['label' => $label, 'value' => $value, 'sort' => $i]);
            }

            $this->line("  + {$item['name']} (".count($gallery).' galeri görseli)');
        }

        Setting::put('site_phone', '+90 549 800 25 10');

        $this->info('Katalog kuruldu. Toplam ürün: '.Product::count());

        return self::SUCCESS;
    }

    /**
     * @return array{0: ?string, 1: array<int, string>}
     */
    private function resolveImages(string $slug): array
    {
        $dir = storage_path("app/public/products/{$slug}");
        $cover = null;
        $gallery = [];

        if (is_file("{$dir}/cover.png")) {
            $cover = "products/{$slug}/cover.png";
        }

        $files = glob("{$dir}/g*.webp") ?: [];
        natsort($files);
        $files = array_values($files);

        foreach ($files as $file) {
            $gallery[] = 'products/'.$slug.'/'.basename($file);
        }

        if ($cover === null && $gallery !== []) {
            $cover = array_shift($gallery);
        }

        return [$cover, $gallery];
    }

    /**
     * @return array<string, array{name: string, icon: string, description: string}>
     */
    private function categories(): array
    {
        return [
            'akilli-cihazlar' => [
                'name' => 'Akıllı Cihazlar',
                'icon' => 'cpu',
                'description' => 'Akıllı telefon ve takip cihazlarıyla hayatı kolaylaştıran teknoloji.',
            ],
            'rc-oyuncak' => [
                'name' => 'RC & Oyuncak',
                'icon' => 'gamepad',
                'description' => 'Uzaktan kumandalı araçlar ve eğlence dolu oyuncaklar.',
            ],
            'muzik-eglence' => [
                'name' => 'Müzik & Eğlence',
                'icon' => 'music',
                'description' => 'Taşınabilir dijital enstrümanlarla her yerde müzik keyfi.',
            ],
            'zeka-egitici' => [
                'name' => 'Zeka & Eğitici Oyunlar',
                'icon' => 'puzzle',
                'description' => 'Yaratıcılığı ve odaklanmayı geliştiren zeka oyunları.',
            ],
            'guvenlik-outdoor' => [
                'name' => 'Güvenlik & Outdoor',
                'icon' => 'shield',
                'description' => 'Yolda, kampta ve acil durumlarda güvende kalmanızı sağlayan ürünler.',
            ],
            'kisisel-bakim' => [
                'name' => 'Kişisel Bakım',
                'icon' => 'sparkles',
                'description' => 'Günlük bakım ve konfor için pratik kişisel bakım ürünleri.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function products(): array
    {
        return [
            [
                'category' => 'muzik-eglence',
                'name' => 'INWELT 9 Pedli Dijital Davul Seti',
                'slug' => 'dijital-davul-seti-9-pedli',
                'seller_url' => 'https://kacmasa.com/inwelt-pedli-dijital-davul-seti-tasinabilir-ve-eglenceli-bateri-deneyimi',
                'badge' => 'Çok Satan',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['bestseller', 'deal', 'gift', 'free-shipping'],
                'summary' => 'Taşınabilir 9 pedli dijital davul seti; 2 pedal, kulaklık çıkışı ve AUX girişiyle her yerde profesyonel bateri deneyimi.',
                'description' => '<p>INWELT 9 Pedli Dijital Davul Seti, gerçek bir bateri hissini kompakt ve taşınabilir bir gövdede sunar. Toplam 9 hassas pad ile davul, zil ve hi-hat seslerini parmaklarınızın ucunda yaşar; iki ayak pedalı sayesinde bas davul ve hi-hat kontrolünü gerçekçi şekilde gerçekleştirirsiniz.</p>'
                    .'<p>Kulaklık çıkışı sayesinde kimseyi rahatsız etmeden saatlerce çalışabilir, hoparlör çıkışı ve AUX girişiyle telefonunuzdan müzik açıp eşlik edebilirsiniz. USB ile şarj edilebilen yapısı, kutudan çıkan adaptör, 2 baget ve 2 pedal ile ilk günden itibaren çalmaya hazırdır.</p>'
                    .'<ul><li>9 adet hassas tetikleme padi</li><li>Kulaklık, hoparlör ve AUX bağlantıları</li><li>Çok sayıda davul sesi ve ritim demosu</li><li>Yeni başlayanlar ve hobi sahipleri için ideal</li></ul>',
                'specs' => [
                    ['Pad Sayısı', '9 pad'],
                    ['Pedal', '2 adet (bas + hi-hat)'],
                    ['Bağlantılar', 'Kulaklık, hoparlör, AUX, pedal'],
                    ['Kutu İçeriği', '2 baget, USB kablo, adaptör'],
                    ['Şarj / Güç', 'USB ile şarj / adaptör'],
                    ['Taşınabilirlik', 'Katlanabilir, taşınabilir tasarım'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT i17 Pro Mini Max 5G Mini Akıllı Telefon',
                'slug' => 'i17-pro-mini-akilli-telefon',
                'seller_url' => 'https://kacmasa.com/i17-pro-mini-max-5g-mini-akilli-telefon-yurtdisi-imei-kayitsiz-android-12-512-gb-depolama-375-inc-amoled-ekran-cift-sim-coklu-dil-global-surum',
                'badge' => 'Global Sürüm',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['smart-devices', 'high-rated', 'bestseller', 'free-shipping'],
                'summary' => 'Cebe sığan 3.75 inç AMOLED ekranlı mini akıllı telefon; Android 12, 512 GB depolama, çift SIM ve 5G hatlı tasarım.',
                'description' => '<p>INWELT i17 Pro Mini Max, büyük telefonların tüm konforunu avuç içine sığan bir gövdeye taşıyan mini akıllı telefondur. 3.75 inç canlı AMOLED ekranı, derin siyahları ve canlı renkleriyle videolar ve oyunlarda keyifli bir deneyim sunar.</p>'
                    .'<p>Android 12 işletim sistemi ve 512 GB depolama alanı sayesinde uygulamalarınız, fotoğraflarınız ve müzikleriniz için bol yer bulursunuz. Çift SIM desteği ile iş ve özel hattınızı tek cihazda yönetir, çoklu dil seçenekleriyle global sürüm avantajından faydalanırsınız.</p>'
                    .'<ul><li>3.75 inç AMOLED ekran</li><li>Android 12, çoklu dil desteği</li><li>512 GB geniş depolama</li><li>Çift SIM, taşınması kolay kompakt gövde</li></ul>'
                    .'<p><em>Not: Yurt dışı sürümdür, IMEI kayıtsızdır.</em></p>',
                'specs' => [
                    ['İşletim Sistemi', 'Android 12'],
                    ['Ekran', '3.75 inç AMOLED'],
                    ['Depolama', '512 GB'],
                    ['SIM', 'Çift SIM'],
                    ['Ağ', '5G hat uyumlu'],
                    ['Sürüm', 'Global / çoklu dil'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT Smart Tag Akıllı Takip Cihazı',
                'slug' => 'smart-tag-takip-cihazi',
                'seller_url' => 'https://kacmasa.com/inwelt-smart-tag-akill-android-ve-ios-uyumlu-uzaktan-takip-cihazi',
                'badge' => 'iOS & Android',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['smart-devices', 'bestseller', 'free-shipping', 'fast-delivery', 'high-rated'],
                'summary' => 'iOS ve Android uyumlu akıllı takip etiketi; 60 metreye kadar Bluetooth menzili ve 12 aya varan pil ömrüyle eşyalarınızı asla kaybetmeyin.',
                'description' => '<h3>Kaybetme Derdi Olmadan Gününüzü Yaşayın</h3>'
                    .'<p>INWELT Smart Tag, anahtar, cüzdan, çanta veya değerli eşyalarınızı saniyeler içinde bulmanızı sağlayan akıllı takip etiketidir. Apple cihazlarda <strong>Find My</strong>, Android cihazlarda ise <strong>Google Find Hub</strong> desteğiyle sorunsuz çalışır.</p>'
                    .'<p>60 metreye kadar bağlantı mesafesi sunan gelişmiş Bluetooth teknolojisi sayesinde eşyalarınızın son konumunu uygulama üzerinden kolayca görüntülersiniz. Değiştirilebilir CR2032 pil ile 12 aya kadar kesintisiz kullanım sağlar.</p>'
                    .'<ul><li>iOS ve Android tam uyum</li><li>60 metreye kadar Bluetooth menzili</li><li>12 aya kadar pil ömrü</li><li>Anlık konum takibi ve bildirim</li><li>Anahtar, çanta, valiz ve evcil hayvan takibi için ideal</li></ul>',
                'specs' => [
                    ['Uyumluluk', 'iOS (Find My) + Android (Find Hub)'],
                    ['Bağlantı', 'Bluetooth, 60 m menzil'],
                    ['Pil', 'CR2032, ~12 ay'],
                    ['Renk', 'Siyah / Beyaz'],
                    ['Kullanım', 'Anahtar, çanta, valiz, evcil hayvan'],
                ],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT Mini Kamera Arabalı RC Off-Road Araç KF32',
                'slug' => 'rc-off-road-kamera-araba-kf32',
                'seller_url' => 'https://kacmasa.com/inwelt-mini-kamera-arabali-rc-off-road-arac-kf32-164-fpv-canli-goruntu-aktarimi-uygulama-kontrollu-uzaktan-kumandali-tirmanici-guclu-motorlu-oyuncak-araba-uzun-pil-omru-hediyelik',
                'badge' => 'FPV Kamera',
                'featured' => true,
                'tags' => ['gift', 'bestseller', 'deal', 'fast-delivery', 'flash'],
                'summary' => '1:64 ölçekli, FPV canlı görüntü aktarımlı uzaktan kumandalı off-road araç; uygulama kontrollü, metal gövdeli ve uzun pil ömürlü.',
                'description' => '<p>INWELT Mini Kamera Arabalı RC Off-Road Araç KF32, FPV canlı görüntü aktarımı sayesinde sürüş deneyimini sürücü koltuğuna taşır. Aracın üzerindeki mini kameradan gelen görüntüyü uygulama üzerinden izleyerek tırmanışları ve engelleri ilk ağızdan deneyimlersiniz.</p>'
                    .'<p>Güçlü motoru ve dayanıklı metal gövdesiyle zorlu zeminlerde performans sunar. USB ile şarj edilebilen lityum polimer bataryası uzun süreli kullanım sağlar; uygulama destekli 2.4 GHz kablosuz bağlantı ise geniş bir kontrol alanı verir.</p>'
                    .'<ul><li>FPV canlı görüntü aktarımı</li><li>Uygulama + 2.4 GHz kablosuz kontrol</li><li>Dayanıklı metal gövde, güçlü motor</li><li>USB şarjlı, uzun ömürlü Li-Po batarya</li><li>6 yaş ve üzeri için uygun, hediyelik</li></ul>',
                'specs' => [
                    ['Ölçek', '1:64'],
                    ['Kamera', 'FPV canlı görüntü'],
                    ['Kontrol', 'Uygulama + 2.4 GHz kablosuz'],
                    ['Batarya', 'Şarj edilebilir Li-Po'],
                    ['Malzeme', 'Metal gövde'],
                    ['Yaş Grubu', '6 yaş ve üzeri'],
                ],
            ],
            [
                'category' => 'zeka-egitici',
                'name' => 'INWELT Tangram Zeka Oyunu Seti',
                'slug' => 'tangram-zeka-seti',
                'seller_url' => 'https://kacmasa.com/inwelt-tangram-zeka-ve-zihin-gelisim-oyunu-egitici-akil-ve-mantik-seti',
                'badge' => 'Eğitici',
                'featured' => false,
                'tags' => ['gift', 'new-arrival', 'free-shipping'],
                'summary' => '7 geometrik parçayla 1600\'den fazla figür oluşturabileceğiniz klasik tangram seti; dikkat, sabır ve hayal gücünü geliştirir.',
                'description' => '<p>INWELT Tangram Zeka Oyunu, klasik zeka oyunlarını modern bir sunumla birleştirir. Toplam 7 geometrik parçadan oluşan set, bir kare formundan yola çıkarak yüzlerce farklı şekil ve figür oluşturmanıza imkân tanır.</p>'
                    .'<p>Hayvanlardan nesnelere kadar sayısız tasarım ortaya çıkarabilir, 1600\'den fazla farklı desen ile oyunu her seferinde yeniden keşfedebilirsiniz. Karmaşık kural gerektirmeyen bu oyun, hem çocuklar hem yetişkinler için ideal bir zeka ve eğlence aktivitesidir.</p>'
                    .'<ul><li>7 parçalı klasik tangram seti</li><li>1600+ farklı figür oluşturma imkânı</li><li>Dikkat, odaklanma ve hayal gücünü destekler</li><li>Ailece oynanabilir, 2 yaş ve üzeri</li></ul>',
                'specs' => [
                    ['Parça Sayısı', '7 parça'],
                    ['Figür', '1600+ farklı desen'],
                    ['Yaş Grubu', '2 yaş ve üzeri'],
                    ['Ürün Kodu', 'GT239'],
                    ['Kutu Ölçüsü', '27,2 x 18,8 x 6,8 cm'],
                ],
            ],
            [
                'category' => 'zeka-egitici',
                'name' => 'INWELT Manyetik Blok 192 Parça Puzzle',
                'slug' => 'manyetik-blok-192-parca',
                'seller_url' => 'https://kacmasa.com/inweltmanyetik-blok-192-parca-puzzle',
                'badge' => 'STEM',
                'featured' => true,
                'tags' => ['gift', 'bestseller', 'free-shipping'],
                'summary' => '192 parçalık manyetik yapı blokları seti; çocukların yaratıcılığını, el becerisini ve geometri algısını geliştiren eğitici puzzle.',
                'description' => '<p>INWELT Manyetik Blok 192 Parça Puzzle, güçlü mıknatıslı parçalarla sınırsız tasarım özgürlüğü sunan eğitici bir yapı setidir. Üçgen, kare ve çok yüzeyli parçalar birbirine kolayca kenetlenerek iki boyutlu desenlerden üç boyutlu yapılar oluşturmanıza imkân tanır.</p>'
                    .'<p>Çocukların renk algısını, el-göz koordinasyonunu, yaratıcılığını ve geometrik düşünme becerisini geliştirir. Dayanıklı ve güvenli malzemesiyle uzun süreli, keyifli bir oyun deneyimi sağlar; ailece birlikte oynamaya da uygundur.</p>'
                    .'<ul><li>192 parçalık zengin manyetik set</li><li>Güçlü mıknatıslar, kolay kenetlenme</li><li>2B desenlerden 3B yapılara geçiş</li><li>Yaratıcılık ve STEM becerilerini destekler</li></ul>',
                'specs' => [
                    ['Parça Sayısı', '192 parça'],
                    ['Tür', 'Manyetik yapı blokları'],
                    ['Beceri', 'Yaratıcılık, geometri, koordinasyon'],
                    ['Malzeme', 'Dayanıklı ABS'],
                    ['Yaş Grubu', '3 yaş ve üzeri'],
                ],
            ],
            [
                'category' => 'guvenlik-outdoor',
                'name' => 'INWELT Şarjlı Çok Fonksiyonlu LED Üçgen İkaz Lambası',
                'slug' => 'reflektor-ikaz-lambasi',
                'seller_url' => 'https://kacmasa.com/inwelt-reflektor-isikli-ikaz-lambasi-sarjli-cok-fonksiyonlu-isikli-ucgen-reflektor',
                'badge' => 'Solar + USB',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['deal', 'free-shipping', 'fast-delivery', 'flash'],
                'summary' => '2000 mAh bataryalı, solar ve USB şarjlı çok fonksiyonlu LED üçgen ikaz lambası; powerbank özelliği ve 5 farklı aydınlatma moduyla acil durumların kurtarıcısı.',
                'description' => '<p>INWELT LED Üçgen İkaz Lambası, yolda kaldığınızda, kamp yaparken veya elektrik kesintilerinde güvenle kullanabileceğiniz çok amaçlı bir aydınlatma ve uyarı cihazıdır. Hem üçgen reflektörlü ikaz lambası hem de güçlü çalışma lambası olarak kullanılır.</p>'
                    .'<p>Dahili güneş paneli ve USB şarj desteğiyle her koşulda enerjiye sahip olur; USB çıkışı sayesinde acil durumlarda telefonunuzu da şarj edebilirsiniz. 3 adet COB LED ile geniş alanları aydınlatır, 180° döner askı aparatıyla istediğiniz açıda konumlandırırsınız.</p>'
                    .'<ul><li>2\'si 1 arada: ikaz lambası + çalışma lambası</li><li>Solar panel ve USB şarj desteği</li><li>Powerbank özelliği (USB çıkışı)</li><li>3 x COB LED, 5 farklı aydınlatma modu</li><li>12-15 saat çalışma süresi</li></ul>',
                'specs' => [
                    ['Batarya', '2000 mAh'],
                    ['Şarj', 'Solar panel + USB'],
                    ['LED', '3 x COB LED'],
                    ['Aydınlatma Modu', '5 mod (beyaz/kırmızı/flaşör)'],
                    ['Çalışma Süresi', '12-15 saat'],
                    ['Ekstra', 'Powerbank (USB çıkışı)'],
                ],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT Akıllı Robot Köpek',
                'slug' => 'akilli-robot-kopek',
                'seller_url' => 'https://kacmasa.com/uzaktan-kumandali-sarjli-cok-fonksiyonlu-egitici-ve-ogretici-interaktif-akilli-robot-kopekcin-versiyonu6501557',
                'badge' => 'İnteraktif',
                'featured' => true,
                'tags' => ['gift', 'deal', 'free-shipping'],
                'summary' => 'Uzaktan kumandalı, ışıklı ekranlı akıllı robot köpek; yürüyüş ve hareket kabiliyetiyle çocuklara eğlenceli ve eğitici bir oyun deneyimi sunar.',
                'description' => '<p>INWELT Akıllı Robot Köpek, yeni nesil teknolojiyi eğlenceyle buluşturarak çocuklara keyifli ve etkileşimli bir oyun dünyası sunar. Gerçekçi hareketleri, dikkat çekici ışıklı ekranı ve kolay kullanımlı uzaktan kumandası sayesinde çocukların ilgisini ilk anda üzerine çeker.</p>'
                    .'<p>Hareket edebilme, yürüyebilme ve farklı pozisyonlar alabilme özellikleriyle hayal gücünü harekete geçirirken el-göz koordinasyonu ve motor becerilerinin gelişimine katkı sağlar.</p>'
                    .'<ul><li>Uzaktan kumanda ile kolay kontrol</li><li>Gerçekçi yürüyüş ve hareket kabiliyeti</li><li>Işıklı ekran ile dikkat çekici görünüm</li><li>Dayanıklı plastik malzeme</li><li>3 yaş ve üzeri için uygun</li></ul>',
                'specs' => [
                    ['Kontrol', 'Uzaktan kumanda'],
                    ['Ekran', 'Işıklı ekran'],
                    ['Malzeme', 'Dayanıklı plastik'],
                    ['Kullanım', 'İç mekân'],
                    ['Yaş Grubu', '3 yaş ve üzeri'],
                    ['Kutu İçeriği', 'Robot köpek + uzaktan kumanda'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT Kart Tipi GPS Takip Cihazı',
                'slug' => 'kart-tipi-gps-takip-cihazi',
                'seller_url' => 'https://kacmasa.com/kart-tipi-gps-takip-cihazi-gercek-zamanli-konum-takibi-su-gecirmez-android-ios-uyumlu',
                'badge' => 'GPS Takip',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['smart-devices', 'high-rated', 'free-shipping'],
                'summary' => 'Kart boyutunda, su geçirmez GPS takip cihazı; gerçek zamanlı konum, geçmiş rota ve Android & iOS uyumluluğuyla eşyalarınızı güvenle izleyin.',
                'description' => '<p>INWELT Kart Tipi GPS Takip Cihazı, ince ve şık tasarımıyla cüzdan, çanta, araç ve valiz gibi değerli eşyalarınız için ideal bir takip çözümüdür. Gerçek zamanlı konum takibi ve geçmiş rota görüntüleme ile eşyalarınızı her zaman kontrol altında tutarsınız.</p>'
                    .'<ul><li>Gerçek zamanlı konum takibi</li><li>Geçmiş rota kayıtları</li><li>Çoklu kullanıcı desteği</li><li>3 aya kadar pil ömrü</li><li>Abonelik ücreti yok</li><li>Su geçirmez tasarım</li><li>Android 5.0+ ve iOS 9.0+ uyumlu</li></ul>',
                'specs' => [
                    ['Takip', 'Gerçek zamanlı GPS'],
                    ['Pil', '~3 ay'],
                    ['Abonelik', 'Yok'],
                    ['Su Geçirmezlik', 'Evet'],
                    ['Uyumluluk', 'Android & iOS'],
                    ['Kullanım', 'Cüzdan, araç, çanta, valiz'],
                ],
            ],
            [
                'category' => 'kisisel-bakim',
                'name' => 'INWELT Elektrikli Tırnak Kesici ve Törpüleyici',
                'slug' => 'elektrikli-tirnak-kesici-beyaz',
                'seller_url' => 'https://kacmasa.com/sarjli-otomatik-elektrikli-tirnak-kesici-ve-torpuleyici-2-kademeli-hiz-ayarli-bebek-ve-yetiskinler-icin-guvenli-tirnak-bakim-cihazi-beyaz',
                'badge' => 'Aile Dostu',
                'featured' => false,
                'tags' => ['gift', 'free-shipping'],
                'summary' => '2 kademeli hız ayarlı, LED aydınlatmalı ve şarj edilebilir tırnak bakım cihazı; bebek, çocuk ve yetişkinler için güvenli kesim ve törpüleme.',
                'description' => '<p>INWELT Elektrikli Tırnak Kesici, tırnaklarınızı keser, şekillendirir ve pürüzsüz hale getiren 3\'ü 1 arada bakım cihazıdır. Akıllı tırnak toplama sistemi sayesinde kesim sırasında oluşan parçacıkları haznede toplar.</p>'
                    .'<ul><li>2 kademeli hız ayarı</li><li>LED aydınlatmalı tasarım</li><li>Şarj edilebilir, kablosuz kullanım</li><li>Kompakt ve taşınabilir</li><li>Tüm aile için güvenli ergonomi</li></ul>',
                'specs' => [
                    ['Hız', '2 kademe'],
                    ['Aydınlatma', 'Dahili LED'],
                    ['Şarj', 'USB şarjlı'],
                    ['Renk', 'Beyaz'],
                    ['Kullanım', 'Bebek, çocuk, yetişkin'],
                    ['Kutu İçeriği', 'Cihaz + USB kablo + kılavuz'],
                ],
            ],
            [
                'category' => 'muzik-eglence',
                'name' => 'INWELT 49 Tuşlu Roll-Up Elektronik Piyano',
                'slug' => 'roll-up-piyano-49-tus',
                'seller_url' => 'https://kacmasa.com/49-tuslu-katlanabilir-silikon-roll-up-elektronik-piyano-tasinabilir-esnek-klavye',
                'badge' => 'Taşınabilir',
                'featured' => true,
                'tags' => ['gift', 'bestseller', 'deal', 'free-shipping'],
                'summary' => 'Katlanabilir silikon 49 tuşlu roll-up piyano; dahili hoparlör, 16 ton, 10 ritim ve kayıt özelliğiyle her yerde müzik pratiği.',
                'description' => '<p>INWELT 49 Tuşlu Roll-Up Elektronik Piyano, esnek silikon yapısı sayesinde müziği dilediğiniz her ortama taşır. Hafif ve katlanabilir tasarımı ile çantada kolayca taşınır.</p>'
                    .'<ul><li>49 tuşlu esnek klavye</li><li>16 ses tonu, 10 ritim, 6 demo</li><li>Dahili stereo hoparlör</li><li>Kayıt ve oynatma fonksiyonu</li><li>Kulaklık desteği ile sessiz çalışma</li><li>Otomatik uyku modu</li></ul>',
                'specs' => [
                    ['Tuş Sayısı', '49'],
                    ['Ton / Ritim', '16 ton, 10 ritim'],
                    ['Hoparlör', 'Dahili stereo'],
                    ['Güç', '3 x AA pil veya 6V adaptör'],
                    ['Malzeme', 'Silikon + ABS panel'],
                    ['Taşınabilirlik', 'Katlanabilir roll-up'],
                ],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT RC Mini Ekskavatör Oyuncağı',
                'slug' => 'rc-mini-ekskavator-oyuncak',
                'seller_url' => 'https://kacmasa.com/uzaktan-kumandali-mini-ekskavator-oyuncagi-164-olcekli-sarjli-rc-is-makinesi-stem-egitici-oyuncak-programlama-ogrenme-araci-erkek-cocuk-hediyesi',
                'badge' => 'STEM',
                'featured' => true,
                'tags' => ['gift', 'bestseller', 'deal'],
                'summary' => '1:64 ölçekli şarjlı RC mini ekskavatör; uzaktan kumanda ve mobil uygulama ile programlama modu, LED ışıklar ve STEM eğitim desteği.',
                'description' => '<p>INWELT RC Mini Ekskavatör, sadece bir oyuncak değil; çocuklara ve teknoloji meraklılarına hitap eden akıllı bir kontrol sistemidir. 2.4 GHz uzaktan kumanda ve mobil uygulama desteği sunar.</p>'
                    .'<ul><li>1:64 ölçekli gerçekçi tasarım</li><li>Uzaktan kumanda + uygulama kontrolü</li><li>Programlama ve rota planlama modu</li><li>LED far ve ışık sistemi</li><li>Şarj edilebilir batarya</li><li>6 yaş ve üzeri STEM oyuncağı</li></ul>',
                'specs' => [
                    ['Ölçek', '1:64'],
                    ['Kontrol', '2.4 GHz + mobil uygulama'],
                    ['Mod', 'Programlama / rota planlama'],
                    ['LED', 'Ön ve arka farlar'],
                    ['Batarya', 'Şarj edilebilir'],
                    ['Yaş Grubu', '6 yaş ve üzeri'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT Bluetooth Çeviri Gözlüğü',
                'slug' => 'bluetooth-ceviri-gozlugu',
                'seller_url' => 'https://kacmasa.com/akilli-bluetooth-ceviri-gozlugu-144-dil-destegi-mikrofonlu-ve-hoparlorlu',
                'badge' => '144 Dil',
                'featured' => true,
                'advantageous' => true,
                'tags' => ['smart-devices', 'high-rated', 'deal', 'flash'],
                'summary' => '144 dil destekli akıllı çeviri gözlüğü; Bluetooth bağlantı, çift mikrofonlu AI gürültü engelleme ve eller serbest görüşme.',
                'description' => '<p>INWELT Bluetooth Çeviri Gözlüğü, yapay zeka destekli çeviri teknolojisiyle 144 farklı dilde anlık iletişim sunar. İş görüşmeleri, seyahat ve günlük kullanım için idealdir.</p>'
                    .'<ul><li>144 dilde anlık çeviri</li><li>3 farklı çeviri modu</li><li>360° surround ses</li><li>AI gürültü engelleme</li><li>5 saat müzik / 4 saat görüşme</li><li>Dokunmatik çağrı ve müzik kontrolü</li></ul>',
                'specs' => [
                    ['Dil Desteği', '144 dil'],
                    ['Bağlantı', 'Bluetooth'],
                    ['Mikrofon', 'Çift AI mikrofon'],
                    ['Pil', '150 mAh'],
                    ['Müzik Süresi', '~5 saat'],
                    ['Görüşme Süresi', '~4 saat'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT USB-C Hızlı Şarj Adaptörü',
                'slug' => 'usb-c-hizli-sarj-adaptoru',
                'seller_url' => 'https://kacmasa.com/akilli-usb-c-hizli-sarj-adaptoru-ai-otomatik-sarj-kesme-teknolojili-guvenli-guc-kesme-ozelligi-yumusak-silikon-korumali-seyahat-dostu-hizli-sarj-cihazi',
                'badge' => 'AI Şarj Kesme',
                'featured' => true,
                'tags' => ['flash', 'deal', 'free-shipping', 'fast-delivery'],
                'summary' => 'AI otomatik şarj kesme teknolojili USB-C hızlı şarj adaptörü; 140W güç, silikon koruma ve seyahat dostu kompakt gövde.',
                'description' => '<p>INWELT USB-C Hızlı Şarj Adaptörü, yapay zeka destekli akıllı çip ile pil tamamen dolduğunda şarjı otomatik durdurarak güvenli kullanım sağlar. Aşırı akım, voltaj ve sıcaklık korumaları ile desteklenir.</p>'
                    .'<ul><li>AI otomatik şarj kesme</li><li>140W yüksek güç çıkışı</li><li>Yumuşak silikon port koruması</li><li>Dinamik LED şarj göstergesi</li><li>4 x 3 x 2 cm kompakt boyut</li><li>Ev, ofis ve seyahat için ideal</li></ul>',
                'specs' => [
                    ['Çıkış', '140W USB-C'],
                    ['Teknoloji', 'AI şarj kesme'],
                    ['Koruma', 'Aşırı akım / voltaj / ısı'],
                    ['Boyut', '4 x 3 x 2 cm'],
                    ['Malzeme', 'ABS + silikon koruma'],
                    ['Kullanım', 'Telefon, tablet, USB-C cihazlar'],
                ],
            ],
            ...$this->kacmasaNewProducts(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function kacmasaNewProducts(): array
    {
        return [
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT RC Forklift Oyuncağı',
                'slug' => 'rc-forklift-oyuncak',
                'seller_url' => 'https://kacmasa.com/rc-forklift-oyuncagi-uzaktan-kumandali-124-olcekli-sarjli-is-makinesi-sesli-isikli-yuk-kaldirma-fonksiyonlu-erkek-cocuk-oyuncagi-hediye',
                'badge' => 'Yeni',
                'featured' => true,
                'tags' => ['new-arrival', 'gift', 'deal'],
                'summary' => '1/24 ölçekli uzaktan kumandalı forklift; sesli, ışıklı ve yük kaldırma fonksiyonlu şarjlı RC iş makinesi.',
                'description' => '<p>INWELT RC Forklift, gerçek forklift deneyimini çocuklara taşıyan 1/24 ölçekli uzaktan kumandalı oyuncaktır. Yük kaldırma fonksiyonu, ses ve ışık efektleriyle interaktif oyun sunar.</p>',
                'specs' => [['Ölçek', '1/24'], ['Kontrol', 'Uzaktan kumanda'], ['Özellik', 'Sesli, ışıklı, yük kaldırma'], ['Batarya', 'Şarj edilebilir']],
            ],
            [
                'category' => 'muzik-eglence',
                'name' => 'INWELT Otomatik Kart Karıştırıcı ve Dağıtıcı',
                'slug' => 'otomatik-kart-karistirici',
                'seller_url' => 'https://kacmasa.com/otomatik-kart-karistirici-ve-dagitici-360-doner-kart-makinesi-kablosuz-kumandali-2-8-kisilik-poker-uno-texas-holdem-ve-blackjack-uyumlu',
                'badge' => 'Yeni',
                'tags' => ['new-arrival', 'gift'],
                'summary' => '360° döner, kablosuz kumandalı otomatik kart karıştırıcı; poker, UNO ve blackjack için 2-8 kişilik.',
                'description' => '<p>Oyun gecelerini hızlandıran otomatik kart karıştırıcı ve dağıtıcı. Kablosuz kumanda ile pratik kullanım.</p>',
                'specs' => [['Kontrol', 'Kablosuz kumanda'], ['Oyuncu', '2-8 kişi'], ['Uyumluluk', 'Poker, UNO, Blackjack']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT Yapay Zeka Destekli Robot Köpek',
                'slug' => 'yapay-zeka-robot-kopek',
                'seller_url' => 'https://kacmasa.com/yapay-zeka-destekli-robot-kopek-oyuncagi-ses-kontrollu-uzaktan-kumandali-usb-sarjli-dans-ve-muzik-ozellikli-akilli-robot-kopek',
                'badge' => 'AI',
                'featured' => true,
                'tags' => ['smart-devices', 'gift', 'new-arrival'],
                'summary' => 'Ses kontrollü, uzaktan kumandalı AI robot köpek; dans, müzik ve USB şarjlı akıllı oyuncak.',
                'description' => '<p>Yapay zeka destekli robot köpek; ses komutları, uzaktan kumanda, dans ve müzik özellikleriyle eğlenceli bir deneyim sunar.</p>',
                'specs' => [['Kontrol', 'Ses + uzaktan kumanda'], ['Şarj', 'USB'], ['Özellik', 'Dans, müzik']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT POP MART SKULLPANDA Winter Symphony',
                'slug' => 'pop-mart-skullpanda-winter',
                'seller_url' => 'https://kacmasa.com/inwelt-pop-mart-skullpanda-winter-symphony-series-plush-single-blind-box',
                'badge' => 'Koleksiyon',
                'tags' => ['gift', 'new-arrival'],
                'summary' => 'POP MART SKULLPANDA Winter Symphony serisi peluş blind box koleksiyon ürünü.',
                'description' => '<p>INWELT x POP MART SKULLPANDA Winter Symphony serisi tekli blind box. Koleksiyon ve hediye için ideal.</p>',
                'specs' => [['Seri', 'Winter Symphony'], ['Tür', 'Blind box peluş']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT POP MART LABUBU Big into Energy',
                'slug' => 'pop-mart-labubu-blind-box',
                'seller_url' => 'https://kacmasa.com/pop-mart-labubu-the-monsters-big-into-energy-series-blind-box',
                'badge' => 'Koleksiyon',
                'featured' => true,
                'tags' => ['gift', 'new-arrival', 'bestseller'],
                'summary' => 'POP MART LABUBU THE MONSTERS Big into Energy serisi blind box.',
                'description' => '<p>POP MART LABUBU Big into Energy serisi koleksiyon figürü. Sürpriz kutu formatında.</p>',
                'specs' => [['Seri', 'Big into Energy'], ['Tür', 'Blind box']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT Stitch Labubu Adventure Pendant',
                'slug' => 'stitch-labubu-pendant',
                'seller_url' => 'https://kacmasa.com/stitch-labubu-adventure-series-vinyl-plush-pendant6882167',
                'badge' => 'Limited',
                'tags' => ['gift', 'new-arrival'],
                'summary' => 'Stitch Labubu Adventure serisi vinyl peluş kolye / pendant koleksiyon parçası.',
                'description' => '<p>Stitch Labubu Adventure serisi vinyl plush pendant. Çanta ve anahtarlık aksesuarı olarak kullanılabilir.</p>',
                'specs' => [['Seri', 'Adventure'], ['Tür', 'Vinyl plush pendant']],
            ],
            [
                'category' => 'kisisel-bakim',
                'name' => 'INWELT Smart Scalp Massager',
                'slug' => 'smart-scalp-massager',
                'seller_url' => 'https://kacmasa.com/inwelt-smart-scalp-massager-derinlemesine-sac-derisi-masaj-cihazi',
                'badge' => 'Yeni',
                'tags' => ['new-arrival', 'gift', 'free-shipping'],
                'summary' => 'Derinlemesine saç derisi masajı sunan akıllı scalp massager cihazı.',
                'description' => '<p>INWELT Smart Scalp Massager, saç derisini rahatlatır ve günlük bakım rutinine konfor katar.</p>',
                'specs' => [['Kullanım', 'Saç derisi masajı'], ['Şarj', 'USB şarjlı']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT 8 Kanallı Paletli Vinç Oyuncağı',
                'slug' => 'rc-paletli-vinc-8-kanal',
                'seller_url' => 'https://kacmasa.com/inwelt-uzaktan-kumandali-sarjli-8-kanalli-paletli-vinc-oyuncak',
                'badge' => 'RC',
                'tags' => ['gift', 'new-arrival'],
                'summary' => '8 kanallı uzaktan kumandalı şarjlı paletli vinç oyuncağı; gerçekçi iş makinesi deneyimi.',
                'description' => '<p>Çok kanallı kumanda ile kova ve şasi hareketlerini ayrı ayrı kontrol edebileceğiniz paletli vinç oyuncağı.</p>',
                'specs' => [['Kanal', '8 kanal'], ['Kontrol', 'Uzaktan kumanda'], ['Batarya', 'Şarj edilebilir']],
            ],
            [
                'category' => 'zeka-egitici',
                'name' => 'INWELT Akıllı Tic Tac Toe Zeka Oyunu',
                'slug' => 'akilli-tic-tac-toe',
                'seller_url' => 'https://kacmasa.com/inwelt-akilli-tic-tac-to-2-kisilik-oyunlar-eglenceli-ve-egitici-bir-zeka-oyunu-deneyimi-sos',
                'badge' => 'Eğitici',
                'tags' => ['gift', 'new-arrival'],
                'summary' => '2 kişilik akıllı Tic Tac Toe zeka oyunu; eğlenceli ve eğitici SOS formatında.',
                'description' => '<p>INWELT Akıllı Tic Tac Toe, klasik XOX oyununu elektronik ve interaktif formata taşır. Aile oyunları için ideal.</p>',
                'specs' => [['Oyuncu', '2 kişi'], ['Tür', 'Elektronik zeka oyunu']],
            ],
            [
                'category' => 'guvenlik-outdoor',
                'name' => 'INWELT Gizli Kamera ve Sinyal Dedektörü',
                'slug' => 'gizli-kamera-dedektoru',
                'seller_url' => 'https://kacmasa.com/inwelt-gizli-kamera-ve-sinyal-dedektoru-sarjli-kablosuz-guvenlik-tarayici',
                'badge' => 'Güvenlik',
                'featured' => true,
                'tags' => ['deal', 'free-shipping'],
                'summary' => 'Şarjlı kablosuz gizli kamera ve sinyal dedektörü; otel ve seyahat güvenliği için tarayıcı.',
                'description' => '<p>Gizli kamera ve RF sinyallerini tespit eden taşınabilir güvenlik tarayıcı. Seyahat ve konaklama güvenliği için pratik.</p>',
                'specs' => [['Şarj', 'USB şarjlı'], ['Tespit', 'Kamera + sinyal'], ['Kullanım', 'Seyahat / otel']],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT Katlanabilir Bluetooth Klavye',
                'slug' => 'katlanabilir-bluetooth-klavye',
                'seller_url' => 'https://kacmasa.com/katlanabilir-bluetooth-klavye-turkce-q-touchpadli-type-c-sarjli-tablet-telefon-ve-laptop-uyumlu',
                'badge' => 'Q Klavye',
                'featured' => true,
                'tags' => ['smart-devices', 'new-arrival', 'free-shipping'],
                'summary' => 'Türkçe Q touchpad\'li katlanabilir Bluetooth klavye; tablet, telefon ve laptop uyumlu Type-C şarj.',
                'description' => '<p>Taşınabilir katlanır Bluetooth klavye; entegre touchpad ile mobil üretkenlik için ideal çözüm.</p>',
                'specs' => [['Bağlantı', 'Bluetooth'], ['Klavye', 'Türkçe Q'], ['Şarj', 'Type-C']],
            ],
            [
                'category' => 'guvenlik-outdoor',
                'name' => 'INWELT Dijital Bagaj Tartısı 50 Kg',
                'slug' => 'dijital-bagaj-tartisi',
                'seller_url' => 'https://kacmasa.com/inwelt-dijital-bagaj-tartisi-50-kg-lcd-ekranli-hassas-valiz-terazisi-dara-ozellikli-seyahat-icin-tasinabilir',
                'badge' => 'Seyahat',
                'tags' => ['free-shipping', 'gift'],
                'summary' => '50 kg kapasiteli LCD ekranlı dijital bagaj tartısı; dara özellikli hassas valiz terazisi.',
                'description' => '<p>Seyahat öncesi bagaj ağırlığını kontrol etmek için kompakt dijital tartı. LCD ekran ve dara fonksiyonu.</p>',
                'specs' => [['Kapasite', '50 kg'], ['Ekran', 'LCD'], ['Özellik', 'Dara / tare']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT LED Işıklı Topaç Oyuncak',
                'slug' => 'led-isikli-topac-oyuncak',
                'seller_url' => 'https://kacmasa.com/inwelt-led-isikli-topac-oyuncak-donen-gyroskop-fidget-spinner-stres-giderici-cocuk-oyuncagi',
                'badge' => 'Yeni',
                'tags' => ['new-arrival', 'gift'],
                'summary' => 'LED ışıklı dönen topaç / gyroskop; fidget spinner tarzı stres giderici çocuk oyuncağı.',
                'description' => '<p>Işıklı topaç oyuncak; dönme efektleriyle eğlenceli ve stres giderici oyun deneyimi.</p>',
                'specs' => [['Özellik', 'LED ışık'], ['Tür', 'Gyroskop / topaç']],
            ],
            [
                'category' => 'rc-oyuncak',
                'name' => 'INWELT Mini Drone HD Kamera',
                'slug' => 'mini-drone-hd-kamera',
                'seller_url' => 'https://kacmasa.com/inwelt-mini-drone-hd-kamera-fotograf-ve-video-cekimi-optical-hover-destekli',
                'badge' => 'HD Kamera',
                'featured' => true,
                'tags' => ['smart-devices', 'new-arrival', 'gift'],
                'summary' => 'HD kamera, fotoğraf ve video çekimi; optical hover destekli kompakt mini drone.',
                'description' => '<p>INWELT Mini Drone, HD kamera ile fotoğraf ve video çeker. Optical hover ile stabil uçuş sağlar.</p>',
                'specs' => [['Kamera', 'HD fotoğraf / video'], ['Hover', 'Optical hover'], ['Boyut', 'Mini / taşınabilir']],
            ],
        ];
    }
}
