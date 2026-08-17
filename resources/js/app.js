import './bootstrap';
import Alpine from 'alpinejs';
import { initAnalytics, initWebVitals } from './analytics';
import { initNavSearchSuggest } from './nav-search-suggest';
import { initProductListingFilters } from './product-listing-filters';
import { initNavPromoBar } from './nav-promo-bar';
import { initScrollRows } from './scroll-row';
import { initScrollReveal } from './scroll-reveal';
import { initHeroAmbient } from './hero-ambient';
import { productGallery } from './product-gallery';

window.Alpine = Alpine;
window.productGallery = productGallery;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initAnalytics();
    initWebVitals();
    initNavPromoBar();
    initScrollRows();
    initProductListingFilters();
    initNavSearchSuggest();
    initScrollReveal();
    initHeroAmbient();
});
