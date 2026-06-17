const PROMO_HIDE_THRESHOLD = 16;
const PROMO_SHOW_THRESHOLD = 4;
const PROMO_MIN_WIDTH = 640;

export function initNavPromoBar() {
    const navbar = document.getElementById('navbar');
    if (!navbar?.querySelector('.nav-promo-bar')) {
        return;
    }

    const smQuery = window.matchMedia(`(min-width: ${PROMO_MIN_WIDTH}px)`);
    let ticking = false;
    let promoHidden = false;

    function setPromoHidden(hidden) {
        if (promoHidden === hidden) {
            return;
        }

        promoHidden = hidden;
        navbar.classList.toggle('header--promo-hidden', hidden);
    }

    function update() {
        if (!smQuery.matches) {
            setPromoHidden(false);
            ticking = false;
            return;
        }

        const y = window.scrollY;

        if (y > PROMO_HIDE_THRESHOLD) {
            setPromoHidden(true);
        } else if (y <= PROMO_SHOW_THRESHOLD) {
            setPromoHidden(false);
        }

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    smQuery.addEventListener('change', update);
    update();
}
