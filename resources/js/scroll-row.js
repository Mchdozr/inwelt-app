export function initScrollRows() {
    document.querySelectorAll('[data-scroll-row]').forEach((wrap) => {
        const track = wrap.querySelector('[data-scroll-row-track]');
        const prev = wrap.querySelector('[data-scroll-row-prev]');
        const next = wrap.querySelector('[data-scroll-row-next]');
        if (!track || !prev || !next) return;

        function scrollStep() {
            const item = track.querySelector(':scope > *');
            if (!item) return Math.round(track.clientWidth * 0.75);
            const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
            return item.offsetWidth + gap;
        }

        function updateButtons() {
            const maxScroll = track.scrollWidth - track.clientWidth;
            prev.disabled = track.scrollLeft <= 1;
            next.disabled = maxScroll <= 1 || track.scrollLeft >= maxScroll - 1;
        }

        prev.addEventListener('click', () => {
            track.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
        });
        next.addEventListener('click', () => {
            track.scrollBy({ left: scrollStep(), behavior: 'smooth' });
        });
        track.addEventListener('scroll', updateButtons, { passive: true });
        window.addEventListener('resize', updateButtons);
        track.querySelectorAll('img').forEach((img) => {
            if (!img.complete) img.addEventListener('load', updateButtons);
        });
        updateButtons();
    });
}
