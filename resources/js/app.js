import './bootstrap';
import { initAnalytics } from './analytics';
import { initNavSearchSuggest } from './nav-search-suggest';
import { initProductListingFilters } from './product-listing-filters';
import { initScrollRows } from './scroll-row';

document.addEventListener('DOMContentLoaded', () => {
    initAnalytics();
    initScrollRows();
    initProductListingFilters();
    initNavSearchSuggest();
});
