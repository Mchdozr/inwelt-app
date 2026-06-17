<?php

namespace App\Http\Controllers;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'INWELT üzerinden doğrudan satın alabilir miyim?',
                'answer' => 'INWELT bir marka vitrinidir. Satın alma işlemi Kacmasa mağazası veya ilgili pazaryeri bağlantıları üzerinden tamamlanır.',
            ],
            [
                'question' => 'Fiyat bilgisini nereden görebilirim?',
                'answer' => 'INWELT ürün fiyatı göstermez. Güncel fiyat ve stok için Kacmasa veya ilgili pazaryeri bağlantısını kullanın.',
            ],
            [
                'question' => 'Kargo ve iade koşulları nelerdir?',
                'answer' => 'Kargo, iade ve ödeme koşulları satın almayı yaptığınız kanala (Kacmasa, Trendyol, Hepsiburada) göre değişir. INWELT satış operasyonu yürütmez.',
            ],
            [
                'question' => 'Ürün etiketleri (Fırsat, Kargo Bedava vb.) ne anlama geliyor?',
                'answer' => 'Liste filtreleri ve rozetler, ürünün kampanya veya özellik bilgisine göre işaretlenir. “Fiyatı Düştü” rozeti fırsat/avantajlı ürünler için kullanılır; güncel fiyat Kacmasa’da görüntülenir.',
            ],
            [
                'question' => 'Toptan veya kurumsal sipariş verebilir miyim?',
                'answer' => 'Evet. İletişim formu veya WhatsApp hattı üzerinden bize ulaşabilirsiniz; sizi doğru satış kanalına yönlendiririz.',
            ],
        ];

        return view('pages.faq', compact('faqs'));
    }
}
