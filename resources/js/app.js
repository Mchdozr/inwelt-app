import './bootstrap';
import { initAnalytics } from './analytics';
import { initNavSearchSuggest } from './nav-search-suggest';
import { initProductListingFilters } from './product-listing-filters';
import { initNavPromoBar } from './nav-promo-bar';
import { initScrollRows } from './scroll-row';

import { initScrollReveal } from './scroll-reveal';
import { initHeroAmbient } from './hero-ambient';

document.addEventListener('DOMContentLoaded', () => {
    initAnalytics();
    initNavPromoBar();
    initScrollRows();
    initProductListingFilters();
    initNavSearchSuggest();
    initScrollReveal();
    initHeroAmbient();
});
