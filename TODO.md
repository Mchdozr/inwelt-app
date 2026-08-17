# Inwelt — Görev Listesi

## Tamamlanan

- [x] Faz 1: Laravel kurulumu, Tailwind+Vite, Git repo
- [x] Faz 2: Migrations, modeller, seeder
- [x] Faz 3: Filament admin panel
- [x] Faz 4: Blade frontend (ana sayfa, ürünler, iletişim)
- [x] Faz 5: SEO, sitemap, cache
- [x] Faz 6: Plesk deploy, MariaDB, SSL, production .env, migrate
- [x] İlk kategori/ürün girişleri — 3 kategori, 9 ürün
- [x] Admin kategori/ürün kayıt ekranları düzeltildi
- [x] Strateji planı: GA4/GTM, UTM, WhatsApp, iletişim maili
- [x] Fiyat alanları, SSS, rehberler, Kacmasa sync komutu
- [x] SEO 2026: schema (AggregateOffer), Guide CMS, GSC/Bing/IndexNow, CWV RUM, E-E-A-T sayfaları

## Bekleyen (production)

- [ ] `.env` içine `GA4_MEASUREMENT_ID` / `GTM_CONTAINER_ID` / `GOOGLE_SITE_VERIFICATION` / `BING_SITE_VERIFICATION` / `INDEXNOW_KEY` ekle
- [ ] Google Search Console + Bing Webmaster’a sitemap index gönder
- [ ] `php artisan inwelt:rotate-admin-password` ile admin şifresini değiştir
- [ ] `php artisan migrate` + `php artisan db:seed --class=GuideSeeder` (canlıda)
- [ ] Plesk cron (her dakika): `* * * * * cd /path/to/inwelt-app && php artisan schedule:run` — günlük 04:00 `inwelt:sync-marketplace-prices`, Pazartesi 03:00 `inwelt:sync-kacmasa`, Salı 05:00 `inwelt:ping-indexnow`
- [ ] Off-page: `docs/seo-offpage-playbook.md`
