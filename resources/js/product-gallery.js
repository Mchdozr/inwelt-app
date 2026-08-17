export function productGallery(images) {
    return {
        images,
        activeIndex: 0,
        modalOpen: false,
        init() {
            this.$watch('modalOpen', (open) => {
                document.documentElement.classList.toggle('gallery-lightbox-open', open);
            });
        },
        get activeImage() {
            return this.images[this.activeIndex] ?? { src: '', alt: '' };
        },
        setActive(index) {
            this.activeIndex = index;
            this.$nextTick(() => {
                this.scrollThumbIntoView(index);
                this.scrollLightboxThumbIntoView(index);
            });
        },
        scrollThumbs(direction) {
            const track = this.$refs.thumbTrack;

            if (! track) {
                return;
            }

            track.scrollBy({ left: direction * Math.max(track.clientWidth * 0.75, 200), behavior: 'smooth' });
        },
        scrollThumbIntoView(index) {
            const track = this.$refs.thumbTrack;
            const thumb = track?.children[index];
            thumb?.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
        },
        scrollLightboxThumbIntoView(index) {
            const track = this.$refs.lightboxThumbTrack;
            const thumb = track?.children[index];
            thumb?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        },
        handleKeydown(event) {
            if (! this.modalOpen) {
                return;
            }

            if (event.key === 'Escape') {
                this.closeLightbox();
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                this.next();
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                this.prev();
            }
        },
        openLightbox(index) {
            if (! this.images.length) {
                return;
            }

            this.activeIndex = index;
            this.modalOpen = true;
        },
        closeLightbox() {
            this.modalOpen = false;
        },
        next() {
            this.activeIndex = (this.activeIndex + 1) % this.images.length;
        },
        prev() {
            this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
        },
    };
}
