<?php

namespace App\Http\Controllers;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'INWELT üzerinden doğrudan satın alabilir miyim?',
                'answer' => 'INWELT bir marka vitrinidir. Satın alma işlemi Kacmasa, Trendyol veya Hepsiburada bağlantıları üzerinden tamamlanır.',
            ],
            [
                'question' => 'Fiyat bilgisi nereden geliyor?',
                'answer' => 'Ürün sayfalarında gördüğünüz fiyat aralığı Kacmasa, Trendyol ve Hepsiburada mağazalarından günlük senkronize edilir. Kesin ödeme tutarı seçtiğiniz pazaryerinde görünür.',
            ],
            [
                'question' => 'Kargo ve iade koşulları nelerdir?',
                'answer' => 'Kargo, iade ve ödeme koşulları satın almayı yaptığınız kanala (Kacmasa, Trendyol, Hepsiburada) göre değişir. INWELT satış operasyonu yürütmez.',
            ],
            [
                'question' => 'Ürün etiketleri (Fırsat, Kargo Bedava vb.) ne anlama geliyor?',
                'answer' => 'Liste filtreleri ve rozetler, ürünün kampanya veya özellik bilgisine göre işaretlenir. “Fiyatı Düştü” rozeti fırsat/avantajlı ürünler için kullanılır.',
            ],
            [
                'question' => 'Toptan veya kurumsal sipariş verebilir miyim?',
                'answer' => 'Evet. İletişim formu veya WhatsApp hattı üzerinden bize ulaşabilirsiniz; sizi doğru satış kanalına yönlendiririz.',
            ],
        ];

        return view('pages.faq', compact('faqs'));
    }
}
