import './bootstrap';
import { initProductListingFilters } from './product-listing-filters';
import { initScrollRows } from './scroll-row';

document.addEventListener('DOMContentLoaded', () => {
    initScrollRows();
    initProductListingFilters();
});
