<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSettings();
        $this->seedCatalog();
    }

    private function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inwelt.com.tr'],
            ['name' => 'Inwelt Admin', 'password' => Hash::make('inwelt2026')]
        );
    }

    private function seedSettings(): void
    {
        $defaults = [
            'site_phone' => '+90 850 000 00 00',
            'site_email' => 'info@inwelt.com.tr',
            'site_address' => 'İstanbul, Türkiye',
            'social_linkedin' => 'https://linkedin.com',
            'social_instagram' => 'https://instagram.com',
            'social_youtube' => 'https://youtube.com',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function seedCatalog(): void
    {
        $catalog = [
            'Akıllı Sistemler' => [
                'icon' => 'cpu',
                'products' => [
                    ['Akıllı Kontrol Ünitesi X1', 'Endüstriyel otomasyon için yüksek performanslı merkezi kontrol ünitesi.', true],
                    ['ProSens IoT Modülü', 'Gerçek zamanlı veri toplama ve izleme için akıllı sensör modülü.', true],
                    ['NetGate Bağlantı Modülü', 'Çoklu protokol destekli endüstriyel ağ geçidi.', false],
                ],
            ],
            'Endüstriyel Çözümler' => [
                'icon' => 'factory',
                'products' => [
                    ['Endüstriyel Panel S2', 'Zorlu ortamlar için dayanıklı dokunmatik HMI paneli.', true],
                    ['Veri Toplama Hub D4', 'Yüksek kapasiteli endüstriyel veri toplama merkezi.', false],
                    ['Güç Modülü PM-200', 'Kesintisiz çalışma için endüstriyel güç yönetim modülü.', false],
                ],
            ],
            'Aksesuarlar' => [
                'icon' => 'plug',
                'products' => [
                    ['Bağlantı Kiti CK-Pro', 'Profesyonel kurulumlar için eksiksiz bağlantı kiti.', false],
                    ['Montaj Rayı Seti MR-12', 'DIN ray uyumlu modüler montaj seti.', false],
                    ['Sensör Kablosu SK-5M', 'Ekranlı, 5 metre endüstriyel sensör kablosu.', false],
                ],
            ],
        ];

        $sort = 0;

        foreach ($catalog as $categoryName => $data) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'icon' => $data['icon'], 'sort' => $sort++, 'is_active' => true]
            );

            $pSort = 0;

            foreach ($data['products'] as [$name, $summary, $featured]) {
                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'badge' => $categoryName,
                        'summary' => $summary,
                        'description' => $summary.' Modüler yapısı sayesinde ihtiyaca göre özelleştirilebilir; CE ve ISO 9001 sertifikalı tesislerimizde üretilir.',
                        'is_featured' => $featured,
                        'is_active' => true,
                        'sort' => $pSort++,
                        'seo_title' => $name.' | Inwelt',
                        'seo_description' => Str::limit($summary, 150),
                    ]
                );

                $product->specs()->delete();
                $product->specs()->createMany([
                    ['label' => 'Model', 'value' => strtoupper(Str::slug($name)), 'sort' => 0],
                    ['label' => 'Garanti', 'value' => '2 yıl', 'sort' => 1],
                    ['label' => 'Sertifika', 'value' => 'CE, RoHS, ISO 9001', 'sort' => 2],
                    ['label' => 'Çalışma Sıcaklığı', 'value' => '-20°C ~ +60°C', 'sort' => 3],
                ]);

                $product->useCases()->delete();
                $product->useCases()->createMany([
                    ['title' => 'Üretim Hatları', 'text' => 'Fabrika otomasyonu ve proses kontrolünde güvenilir performans.', 'sort' => 0],
                    ['title' => 'Enerji Yönetimi', 'text' => 'Akıllı enerji izleme ve optimizasyon uygulamaları.', 'sort' => 1],
                    ['title' => 'Bina Otomasyonu', 'text' => 'Akıllı bina ve tesis yönetim sistemleri.', 'sort' => 2],
                ]);
            }
        }
    }
}
