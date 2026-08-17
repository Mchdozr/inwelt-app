function pushEvent(name, payload = {}) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', name, { ...payload, transport_type: 'beacon' });
    }

    if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ event: name, ...payload });
    }
}

function trackMarketplaceClick(channel, productSlug) {
    pushEvent('outbound_marketplace_click', { channel, product_slug: productSlug || '' });
    pushEvent('click_marketplace', { channel, product_slug: productSlug || '' });
}

function trackWhatsAppClick(context, productSlug) {
    pushEvent('whatsapp_click', { context, product_slug: productSlug || '' });
    pushEvent('click_whatsapp', { context, product_slug: productSlug || '' });
}

function trackPageViews() {
    const product = document.querySelector('[data-analytics-product]');
    if (product) {
        pushEvent('product_view', { product_slug: product.dataset.analyticsProduct });
        pushEvent('view_item', {
            item_id: product.dataset.analyticsProduct,
            item_name: product.dataset.productName || '',
        });
    }

    const category = document.querySelector('[data-analytics-category]');
    if (category) {
        pushEvent('category_view', { category_slug: category.dataset.analyticsCategory });
    }
}

function initCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    if (! banner) {
        return;
    }

    const stored = localStorage.getItem('inwelt-cookie-consent');
    if (! stored) {
        banner.hidden = false;
    }

    banner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        localStorage.setItem('inwelt-cookie-consent', 'accepted');
        banner.hidden = true;
    });

    banner.querySelector('[data-cookie-reject]')?.addEventListener('click', () => {
        localStorage.setItem('inwelt-cookie-consent', 'rejected');
        banner.hidden = true;
    });
}

export function initAnalytics() {
    trackPageViews();
    initCookieBanner();

    document.addEventListener('click', (event) => {
        const marketplaceLink = event.target.closest('[data-track-marketplace]');

        if (marketplaceLink) {
            trackMarketplaceClick(
                marketplaceLink.dataset.trackMarketplace,
                marketplaceLink.dataset.productSlug || '',
            );
            return;
        }

        const whatsappLink = event.target.closest('[data-track-whatsapp]');

        if (whatsappLink) {
            trackWhatsAppClick(
                whatsappLink.dataset.trackWhatsapp,
                whatsappLink.dataset.productSlug || '',
            );
        }
    });

    document.querySelector('form[action*="iletisim"]')?.addEventListener('submit', () => {
        pushEvent('contact_form_submit');
    });

    document.addEventListener('inwelt:search-suggest', (event) => {
        pushEvent('search_suggest_used', { query: event.detail?.query || '' });
    });
}

export async function initWebVitals() {
    try {
        const { onLCP, onINP, onCLS } = await import('web-vitals');
        const send = (metric) => pushEvent('web_vital', {
            metric_name: metric.name,
            value: metric.value,
            rating: metric.rating,
        });
        onLCP(send);
        onINP(send);
        onCLS(send);
    } catch {
        // web-vitals optional
    }
}
