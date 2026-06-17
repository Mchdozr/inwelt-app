function trackMarketplaceClick(channel, productSlug) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', 'click_marketplace', {
            channel,
            product_slug: productSlug || '',
            transport_type: 'beacon',
        });
    }

    if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push({
            event: 'click_marketplace',
            channel,
            product_slug: productSlug || '',
        });
    }
}

function trackWhatsAppClick(context, productSlug) {
    if (typeof window.gtag === 'function') {
        window.gtag('event', 'click_whatsapp', {
            context,
            product_slug: productSlug || '',
            transport_type: 'beacon',
        });
    }

    if (Array.isArray(window.dataLayer)) {
        window.dataLayer.push({
            event: 'click_whatsapp',
            context,
            product_slug: productSlug || '',
        });
    }
}

export function initAnalytics() {
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
}
