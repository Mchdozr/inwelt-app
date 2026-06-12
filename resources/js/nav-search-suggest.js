const DEBOUNCE_MS = 300;
const MIN_CHARS = 2;
const RECENT_KEY = 'inwelt-recent-searches';
const MAX_RECENT = 5;

function escapeHtml(text) {
    const el = document.createElement('span');
    el.textContent = text;
    return el.innerHTML;
}

function getRecentSearches() {
    try {
        const raw = localStorage.getItem(RECENT_KEY);
        const parsed = raw ? JSON.parse(raw) : [];
        return Array.isArray(parsed) ? parsed.filter((item) => typeof item === 'string' && item.trim()) : [];
    } catch {
        return [];
    }
}

function saveRecentSearch(term) {
    const trimmed = term.trim();
    if (trimmed.length < MIN_CHARS) {
        return;
    }

    const recent = getRecentSearches().filter((item) => item.toLowerCase() !== trimmed.toLowerCase());
    recent.unshift(trimmed);
    localStorage.setItem(RECENT_KEY, JSON.stringify(recent.slice(0, MAX_RECENT)));
}

function buildProductItem(product, index) {
    const subtitle = product.badge || product.summary || product.category || '';
    const subtitleHtml = subtitle
        ? `<span class="nav-search-suggest__meta">${escapeHtml(subtitle)}</span>`
        : '';

    return `
        <a
            href="${escapeHtml(product.url)}"
            class="nav-search-suggest__item"
            role="option"
            data-suggest-index="${index}"
            tabindex="-1"
        >
            <span class="nav-search-suggest__thumb">
                <img src="${escapeHtml(product.image)}" alt="" loading="lazy" decoding="async">
            </span>
            <span class="nav-search-suggest__content">
                <span class="nav-search-suggest__name">${escapeHtml(product.name)}</span>
                ${subtitleHtml}
            </span>
            <span class="nav-search-suggest__cta">İncele</span>
        </a>
    `;
}

function buildRecentItem(term, index) {
    return `
        <button
            type="button"
            class="nav-search-suggest__chip"
            role="option"
            data-suggest-index="${index}"
            data-recent-term="${escapeHtml(term)}"
            tabindex="-1"
        >
            <svg class="nav-search-suggest__chip-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>${escapeHtml(term)}</span>
        </button>
    `;
}

class NavSearchSuggest {
    constructor(form) {
        this.form = form;
        this.input = form.querySelector('input[type="search"]');
        this.dropdown = form.querySelector('[data-nav-search-dropdown]');
        this.apiUrl = form.dataset.suggestUrl || '/api/search/suggest';
        this.debounceTimer = null;
        this.abortController = null;
        this.activeIndex = -1;
        this.items = [];
        this.isOpen = false;

        if (!this.input || !this.dropdown) {
            return;
        }

        this.bindEvents();
    }

    bindEvents() {
        this.input.addEventListener('input', () => this.onInput());
        this.input.addEventListener('focus', () => this.onFocus());
        this.input.addEventListener('keydown', (event) => this.onKeydown(event));
        this.form.addEventListener('submit', () => {
            saveRecentSearch(this.input.value);
            this.close();
        });
        this.dropdown.addEventListener('mousedown', (event) => event.preventDefault());
        this.dropdown.addEventListener('click', (event) => this.onDropdownClick(event));
        document.addEventListener('click', (event) => {
            if (!this.form.contains(event.target)) {
                this.close();
            }
        });
    }

    onInput() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.fetchSuggestions(), DEBOUNCE_MS);
    }

    onFocus() {
        const value = this.input.value.trim();
        if (value.length >= MIN_CHARS) {
            this.fetchSuggestions();
        } else {
            this.showRecentOnly();
        }
    }

    async fetchSuggestions() {
        const query = this.input.value.trim();

        if (query.length < MIN_CHARS) {
            this.showRecentOnly();
            return;
        }

        this.abortController?.abort();
        this.abortController = new AbortController();

        this.renderLoading(query);

        try {
            const response = await fetch(`${this.apiUrl}?q=${encodeURIComponent(query)}`, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: this.abortController.signal,
            });

            if (!response.ok) {
                throw new Error('Arama başarısız');
            }

            const data = await response.json();
            this.renderResults(query, data.products || []);
        } catch (error) {
            if (error.name !== 'AbortError') {
                this.renderEmpty(query, 'Arama yapılamadı. Tekrar deneyin.');
            }
        }
    }

    showRecentOnly() {
        const recent = getRecentSearches();
        if (!recent.length) {
            this.close();
            return;
        }

        this.items = recent.map((term) => ({ type: 'recent', term }));
        this.activeIndex = -1;

        const chips = recent.map((term, index) => buildRecentItem(term, index)).join('');

        this.dropdown.innerHTML = `
            <div class="nav-search-suggest__section">
                <p class="nav-search-suggest__heading">Son aramalar</p>
                <div class="nav-search-suggest__chips">${chips}</div>
            </div>
        `;

        this.open();
    }

    renderLoading(query) {
        this.items = [];
        this.activeIndex = -1;
        this.dropdown.innerHTML = `
            <div class="nav-search-suggest__status" role="status" aria-live="polite">
                <span class="nav-search-suggest__spinner" aria-hidden="true"></span>
                "${escapeHtml(query)}" aranıyor…
            </div>
        `;
        this.open();
    }

    renderResults(query, products) {
        this.items = products.map((product) => ({ type: 'product', product }));
        this.activeIndex = -1;

        if (!products.length) {
            this.renderEmpty(query, 'Eşleşen ürün bulunamadı.');
            return;
        }

        const productHtml = products.map((product, index) => buildProductItem(product, index)).join('');
        const viewAllUrl = `/urunler?ara=${encodeURIComponent(query)}`;

        this.dropdown.innerHTML = `
            <div class="nav-search-suggest__section">
                <p class="nav-search-suggest__heading">Ürünler</p>
                <div class="nav-search-suggest__list">${productHtml}</div>
            </div>
            <div class="nav-search-suggest__footer">
                <a href="${viewAllUrl}" class="nav-search-suggest__view-all">
                    Tüm sonuçları gör
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        `;

        this.open();
        this.input.setAttribute('aria-expanded', 'true');
    }

    renderEmpty(query, message) {
        this.items = [];
        this.activeIndex = -1;
        const viewAllUrl = `/urunler?ara=${encodeURIComponent(query)}`;

        this.dropdown.innerHTML = `
            <div class="nav-search-suggest__empty">
                <p>${escapeHtml(message)}</p>
                <a href="${viewAllUrl}" class="nav-search-suggest__view-all nav-search-suggest__view-all--inline">
                    "${escapeHtml(query)}" için tüm sonuçlar
                </a>
            </div>
        `;
        this.open();
    }

    onDropdownClick(event) {
        const chip = event.target.closest('[data-recent-term]');
        if (chip) {
            this.input.value = chip.dataset.recentTerm;
            this.form.requestSubmit();
            return;
        }

        const item = event.target.closest('.nav-search-suggest__item');
        if (item) {
            saveRecentSearch(this.input.value);
            this.close();
        }
    }

    onKeydown(event) {
        if (!this.isOpen) {
            return;
        }

        const selectable = [...this.dropdown.querySelectorAll('[role="option"]')];

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            this.activeIndex = Math.min(this.activeIndex + 1, selectable.length - 1);
            this.highlightItem(selectable);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            this.activeIndex = Math.max(this.activeIndex - 1, 0);
            this.highlightItem(selectable);
        } else if (event.key === 'Enter' && this.activeIndex >= 0 && selectable[this.activeIndex]) {
            event.preventDefault();
            selectable[this.activeIndex].click();
        } else if (event.key === 'Escape') {
            this.close();
        }
    }

    highlightItem(selectable) {
        selectable.forEach((el, index) => {
            el.classList.toggle('is-active', index === this.activeIndex);
        });

        selectable[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
    }

    open() {
        this.dropdown.hidden = false;
        this.form.classList.add('nav-search--open');
        this.isOpen = true;
        this.input.setAttribute('aria-expanded', 'true');
        this.input.setAttribute('aria-controls', this.dropdown.id);
    }

    close() {
        this.dropdown.hidden = true;
        this.form.classList.remove('nav-search--open');
        this.isOpen = false;
        this.activeIndex = -1;
        this.input.setAttribute('aria-expanded', 'false');
    }
}

export function initNavSearchSuggest() {
    document.querySelectorAll('[data-nav-search]').forEach((form) => {
        new NavSearchSuggest(form);
    });
}
