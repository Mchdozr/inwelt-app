let revealObserver = null;

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function ensureRevealObserver() {
    if (revealObserver) {
        return revealObserver;
    }

    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                revealObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -5% 0px',
        },
    );

    return revealObserver;
}

function observeRevealElement(element) {
    if (element.classList.contains('is-revealed')) {
        return;
    }

    ensureRevealObserver().observe(element);
}

function observeStaggerContainers(root = document) {
    root.querySelectorAll('[data-reveal-stagger]').forEach((parent) => {
        const selector = parent.dataset.revealStagger || '.prod-card, .cat-tile, .weekly-picks-card, .explore-card, .value-card, .guide-card, .faq-item, .stat-card, .cta-benefit';
        parent.querySelectorAll(selector).forEach((child, index) => {
            if (child.classList.contains('reveal')) {
                return;
            }

            child.classList.add('reveal');
            child.style.setProperty('--reveal-delay', `${Math.min(index * 0.055, 0.42)}s`);
            observeRevealElement(child);
        });
    });
}

export function refreshScrollReveal(root = document) {
    if (prefersReducedMotion()) {
        root.querySelectorAll('.reveal').forEach((element) => element.classList.add('is-revealed'));

        return;
    }

    root.querySelectorAll('.reveal:not(.is-revealed)').forEach((element) => observeRevealElement(element));
    observeStaggerContainers(root);
}

export function initScrollReveal() {
    if (prefersReducedMotion()) {
        document.querySelectorAll('.reveal').forEach((element) => element.classList.add('is-revealed'));

        return;
    }

    refreshScrollReveal(document);
}
