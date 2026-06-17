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

## Bekleyen (production)

- [ ] `.env` içine `GA4_MEASUREMENT_ID` / `GTM_CONTAINER_ID` ekle
- [ ] `php artisan inwelt:rotate-admin-password` ile admin şifresini değiştir
- [ ] `php artisan migrate` + `php artisan inwelt:sync-kacmasa` (canlıda)
- [ ] Plesk cron: `php artisan schedule:run` (haftalık fiyat senkronu)
