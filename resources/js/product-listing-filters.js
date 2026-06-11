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

async function refreshProductListing({ categorySlug, advantageActive } = {}) {
    const listing = document.querySelector('[data-products-listing]');
    if (!listing) {
        return false;
    }

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
        const response = await fetch(`${productsBaseUrl()}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html',
            },
        });

        if (!response.ok) {
            throw new Error('Filter request failed');
        }

        listing.outerHTML = await response.text();

        params.delete('partial');
        const nextUrl = params.toString()
            ? `${productsBaseUrl()}?${params.toString()}`
            : productsBaseUrl();
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
