function productsBaseUrl() {
    return document.querySelector('[data-product-filters]')?.dataset.productsUrl ?? '/urunler';
}

function currentParams() {
    const params = new URLSearchParams(window.location.search);

    if (window.location.pathname.startsWith('/kategori/')) {
        const slug = window.location.pathname.split('/').filter(Boolean).pop();
        if (slug) {
            params.set('kategori', slug);
        }
    }

    return params;
}

function listingFetchPath() {
    if (window.location.pathname.startsWith('/kategori/')) {
        return window.location.pathname;
    }

    return productsBaseUrl();
}

function syncProductCounts() {
    const listing = document.querySelector('[data-products-listing]');
    if (!listing) {
        return;
    }

    const total = listing.dataset.productsTotal
        ?? listing.querySelector('[data-products-count]')?.textContent?.trim()
        ?? '0';

    document.querySelectorAll('[data-products-count]').forEach((element) => {
        element.textContent = total;
    });
}

function setCategoryNavActive(slug) {
    document.querySelectorAll('[data-category-filter]').forEach((button) => {
        const buttonSlug = button.dataset.categorySlug ?? '';
        const isActive = buttonSlug === (slug ?? '');

        button.classList.toggle('sidebar-cat--active', isActive);
        button.classList.toggle('sidebar-cat--idle', !isActive);

        if (isActive) {
            button.setAttribute('aria-current', 'page');
        } else {
            button.removeAttribute('aria-current');
        }
    });
}

let infiniteObserver = null;
let infiniteLoading = false;

function teardownInfiniteScroll() {
    infiniteObserver?.disconnect();
    infiniteObserver = null;
    infiniteLoading = false;
}

function ensureSentinel(listing) {
    let sentinel = listing.querySelector('[data-infinite-sentinel]');

    if (!sentinel && listing.dataset.hasMore === 'true') {
        sentinel = document.createElement('div');
        sentinel.className = 'products-infinite-sentinel';
        sentinel.dataset.infiniteSentinel = '';
        sentinel.setAttribute('aria-hidden', 'true');
        sentinel.innerHTML = '<span class="products-infinite-sentinel__spinner" data-infinite-spinner hidden></span>';
        listing.appendChild(sentinel);
    }

    return sentinel;
}

async function loadMoreProducts() {
    const listing = document.querySelector('[data-products-listing]');
    const grid = listing?.querySelector('[data-products-grid]');

    if (!listing || !grid || infiniteLoading || listing.dataset.hasMore !== 'true') {
        return;
    }

    const nextPage = Number(listing.dataset.currentPage || '1') + 1;
    const params = currentParams();
    params.set('page', String(nextPage));
    params.set('partial', 'products-grid-items');

    const sentinel = ensureSentinel(listing);
    const spinner = sentinel?.querySelector('[data-infinite-spinner]');

    infiniteLoading = true;
    spinner?.removeAttribute('hidden');

    try {
        const response = await fetch(`${listingFetchPath()}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Infinite scroll request failed');
        }

        const payload = await response.json();
        grid.insertAdjacentHTML('beforeend', payload.html);

        listing.dataset.currentPage = String(payload.current_page);
        listing.dataset.hasMore = payload.has_more ? 'true' : 'false';

        if (!payload.has_more) {
            sentinel?.remove();
            teardownInfiniteScroll();
        } else {
            bindInfiniteScroll();
        }
    } catch {
        listing.dataset.hasMore = 'false';
        sentinel?.remove();
        teardownInfiniteScroll();
    } finally {
        infiniteLoading = false;
        spinner?.setAttribute('hidden', '');
    }
}

function bindInfiniteScroll() {
    const listing = document.querySelector('[data-infinite-scroll]');

    teardownInfiniteScroll();

    if (!listing || listing.dataset.hasMore !== 'true') {
        return;
    }

    const sentinel = ensureSentinel(listing);

    if (!sentinel) {
        return;
    }

    infiniteObserver = new IntersectionObserver(
        (entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                loadMoreProducts();
            }
        },
        { rootMargin: '240px 0px' },
    );

    infiniteObserver.observe(sentinel);
}

async function refreshProductListing({ categorySlug, advantageActive } = {}) {
    const listing = document.querySelector('[data-products-listing]');
    if (!listing) {
        return false;
    }

    teardownInfiniteScroll();

    const params = currentParams();
    params.delete('page');

    if (categorySlug !== undefined) {
        if (categorySlug) {
            params.set('kategori', categorySlug);
        } else {
            params.delete('kategori');
        }
    }

    if (advantageActive !== undefined) {
        if (advantageActive) {
            params.set('avantajli', '1');
        } else {
            params.delete('avantajli');
        }
    }

    params.set('partial', 'products-listing');
    listing.classList.add('products-listing--loading');

    try {
        const response = await fetch(`${listingFetchPath()}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html',
            },
        });

        if (!response.ok) {
            throw new Error('Filter request failed');
        }

        listing.outerHTML = await response.text();
        syncProductCounts();
        bindInfiniteScroll();

        params.delete('partial');
        const nextUrl = params.toString()
            ? `${listingFetchPath()}?${params.toString()}`
            : listingFetchPath();
        window.history.replaceState(null, '', nextUrl);

        if (categorySlug !== undefined) {
            setCategoryNavActive(categorySlug);
        }

        return true;
    } finally {
        document.querySelector('[data-products-listing]')?.classList.remove('products-listing--loading');
    }
}

export function initProductListingFilters() {
    bindInfiniteScroll();

    document.addEventListener('click', async (event) => {
        const categoryButton = event.target.closest('[data-category-filter]');
        if (categoryButton) {
            event.preventDefault();

            const slug = categoryButton.dataset.categorySlug ?? '';
            if (categoryButton.getAttribute('aria-current') === 'page' || categoryButton.disabled) {
                return;
            }

            categoryButton.disabled = true;
            document.querySelectorAll('[data-category-filter]').forEach((button) => {
                button.disabled = true;
            });

            try {
                await refreshProductListing({ categorySlug: slug });
            } finally {
                document.querySelectorAll('[data-category-filter]').forEach((button) => {
                    button.disabled = false;
                });
            }

            return;
        }

        const toggle = event.target.closest('[data-advantage-toggle]');
        if (!toggle) {
            return;
        }

        event.preventDefault();

        if (toggle.disabled) {
            return;
        }

        const wasActive = toggle.getAttribute('aria-checked') === 'true';
        const nextActive = !wasActive;

        toggle.setAttribute('aria-checked', nextActive ? 'true' : 'false');
        toggle.classList.toggle('is-active', nextActive);
        toggle.disabled = true;

        try {
            const ok = await refreshProductListing({ advantageActive: nextActive });
            if (!ok) {
                throw new Error('Listing missing');
            }
        } catch {
            toggle.setAttribute('aria-checked', wasActive ? 'true' : 'false');
            toggle.classList.toggle('is-active', wasActive);
        } finally {
            toggle.disabled = false;
        }
    });
}
