# INWELT SEO — Zamanlı Faz Takvimi

> Cloud Agent / Cursor Automation her Pazartesi bu dosyayı okuyup **due** fazı uygular.
> Plan kaynağı: kullanıcı planı `Inwelt Google SEO` (Faz 0–4).

**Başlangıç:** 2026-08-21  
**Durum dosyası:** Bu dosyanın altındaki checklist güncellenir; tamamlanan maddeler `[x]` yapılır.

---

## Otomasyon talimatı (her tick)

1. Bu dosyayı ve `docs/seo-offpage-playbook.md` dosyasını oku.
2. Bugünün tarihine göre **aktif pencere**deki ilk tamamlanmamış işi bul.
3. Kod işlerini uygula; test çalıştır; commit + `main` push + Plesk deploy (`root@inwelt.com.tr` → `httpdocs` git reset + `optimize:clear`).
4. Hesap işleri (GBP, Merchant, sosyal bio) için kullanıcıya kısa checklist bırak; sen metin şablonlarını hazırla/güncelle.
5. Bu dosyada ilgili checkbox’ları işaretle ve commit et.
6. Faz bitince bir sonrakine geç; tüm fazlar bitince yalnızca aylık ölçüm (Faz 4) çalıştır.

---

## Faz 0 — Güven mesajı (2026-08-21 → hemen)

- [x] Ana sayfa `1000+` / `binlerce ürün` kaldırıldı
- [x] Dinamik vitrin ürün sayısı
- [x] Merchant feed: taze fiyat + `identifier_exists`
- [x] Pazaryeri `rel="sponsored"`
- [x] Yazarlar sitemap-pages

---

## Faz 1 — Entity / off-page (2026-08-21 → 2026-09-04)

Hedef bitiş: **2026-09-04**

- [ ] Google İşletme Profili (adres + https://inwelt.com.tr)
- [ ] Merchant Center: site doğrula + feed `https://inwelt.com.tr/feed/google-merchant.xml`
- [ ] Kacmasa / Trendyol / HB mağaza bio (şablon: playbook)
- [ ] Instagram bio link
- [ ] LinkedIn Company + YouTube açıklama (URL’ler gelince `config/seo.php` sameAs)

**Agent tick (Faz 1):** Kullanıcı hesabı yoksa kod tarafında hazırlık yap; playbook checklist’i hatırlat; sameAs URL varsa commit et.

---

## Faz 2 — İçerik moatı (2026-08-25 → 2026-10-03)

### Sprint A — 2026-08-25 → 2026-09-07
- [ ] Orphan rehber audit: `php artisan inwelt:seo-orphan-audit` → 0 orphan hedefi
- [ ] Cluster 1: Akıllı hoparlör / J1 (ürün FAQ + rehber iç link)
- [ ] Cluster 2: Drift / RC scooter

### Sprint B — 2026-09-08 → 2026-09-21
- [ ] 6 kategoride özgün `seo_content` + FAQ (padding değil)
- [ ] Cluster 3: Çocuk RC / hediye
- [ ] En az 5 şablon rehberi özgünleştir

### Sprint C — 2026-09-22 → 2026-10-03
- [ ] Cluster 4–5: Akıllı takip + outdoor/güvenlik
- [ ] Kalan şablon rehberler
- [ ] Rehber→rehber “ilgili yazılar” (kod)

---

## Faz 3 — Teknik cila (2026-09-01 → 2026-09-14)

- [x] Merchant tazelik + identifier_exists (Faz 0 ile birlikte)
- [x] sponsored rel
- [x] Yazar sitemap
- [ ] Image sitemap
- [ ] Ürün görsellerinde srcset / webp pipeline
- [ ] Consent Mode v2 (çerez reject → GA durur)
- [ ] `g:google_product_category` mapping (kategori bazlı)

---

## Faz 4 — Ölçek (aylık, ilk tick: 2026-09-21)

Her ayın 3. Pazartesi:
- [ ] GSC + playbook 10 sorgu baseline kaydı (Notion/Excel’e özet)
- [ ] Kaliteli katalog büyütme (thin sayfa ekleme)
- [ ] CWV kötü URL’leri düzelt
- [ ] Orphan audit = 0

---

## Deploy notu

```bash
# lokal
git push origin main
# sunucu
ssh root@inwelt.com.tr 'cd /var/www/vhosts/inwelt.com.tr/httpdocs && git fetch origin && git reset --hard origin/main && php artisan optimize:clear'
```
