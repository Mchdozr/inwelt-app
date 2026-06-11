<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
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
                'summary' => $item['summary'],
                'description' => $item['description'],
                'cover_image' => null,
                'is_featured' => $item['featured'] ?? false,
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
                'badge' => 'Çok Satan',
                'featured' => true,
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
                'category' => 'muzik-eglence',
                'name' => 'INWELT Pedli Dijital Davul Seti',
                'slug' => 'dijital-davul-seti-pedli',
                'badge' => 'Yeni',
                'featured' => false,
                'summary' => 'Pedallı, taşınabilir dijital davul seti; kulaklık çıkışı ve AUX girişiyle pratik ve eğlenceli bir bateri deneyimi.',
                'description' => '<p>INWELT Pedli Dijital Davul Seti, müzikle yeni tanışanlar ve hobi olarak çalmak isteyenler için tasarlanmış taşınabilir bir dijital baterdir. Esnek pad yapısı ve ayak pedalları ile gerçekçi bir ritim deneyimi sunar.</p>'
                    .'<p>Kulaklık ve hoparlör çıkışı, pedal girişi ve harici cihaz bağlantısı için AUX girişi ile donatılmıştır. Kutudan çıkan 2 baget, 2 pedal, USB kablo ve adaptör ile kurulum yapmadan dakikalar içinde çalmaya başlarsınız.</p>'
                    .'<ul><li>Esnek, taşınabilir pad yapısı</li><li>Kulaklık ile sessiz çalışma imkânı</li><li>AUX ile müzik eşliğinde pratik yapma</li><li>Hediye için ideal kompakt set</li></ul>',
                'specs' => [
                    ['Pedal', '2 adet'],
                    ['Bağlantılar', 'Kulaklık, hoparlör, AUX, pedal'],
                    ['Kutu İçeriği', '2 baget, USB kablo, adaptör'],
                    ['Şarj / Güç', 'USB ile şarj / adaptör'],
                    ['Kullanım', 'Başlangıç ve hobi seviyesi'],
                ],
            ],
            [
                'category' => 'akilli-cihazlar',
                'name' => 'INWELT i17 Pro Mini Max 5G Mini Akıllı Telefon',
                'slug' => 'i17-pro-mini-akilli-telefon',
                'badge' => 'Global Sürüm',
                'featured' => true,
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
                'badge' => 'iOS & Android',
                'featured' => true,
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
                'badge' => 'FPV Kamera',
                'featured' => true,
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
                'badge' => 'Eğitici',
                'featured' => false,
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
                'badge' => 'STEM',
                'featured' => true,
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
                'badge' => 'Solar + USB',
                'featured' => true,
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
        ];
    }
}
