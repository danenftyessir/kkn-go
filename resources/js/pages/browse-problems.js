// resources/js/pages/browse-problems.js
// javascript untuk interaktivitas pada halaman browse problems

/**
 * smooth scroll dengan GPU acceleration
 */
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        // enable smooth scrolling untuk semua anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    this.scrollTo(target);
                }
            });
        });

        // scroll ke top button
        this.initScrollToTop();
    }

    scrollTo(element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    initScrollToTop() {
        const scrollBtn = document.getElementById('scroll-to-top');
        if (!scrollBtn) return;

        // throttle scroll event untuk performa
        let ticking = false;
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (window.pageYOffset > 300) {
                        scrollBtn.classList.remove('hidden');
                    } else {
                        scrollBtn.classList.add('hidden');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

/**
 * lazy loading untuk images dengan intersection observer
 */
class LazyLoader {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            this.images.forEach(img => imageObserver.observe(img));
        } else {
            // fallback untuk browser yang tidak support intersection observer
            this.images.forEach(img => this.loadImage(img));
        }
    }

    loadImage(img) {
        const src = img.getAttribute('data-src');
        if (!src) return;

        img.src = src;
        img.removeAttribute('data-src');
        img.classList.remove('skeleton');
        
        // fade in animation
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
        img.onload = () => {
            img.style.opacity = '1';
        };
    }
}

/**
 * filter handler untuk browse problems
 */
class FilterHandler {
    constructor() {
        this.form = document.getElementById('filter-form');
        this.provinceSelect = document.querySelector('select[name="province_id"]');
        this.regencySelect = document.querySelector('select[name="regency_id"]');
        this.init();
    }

    init() {
        if (!this.form) return;

        // handle province change untuk load regencies
        if (this.provinceSelect) {
            this.provinceSelect.addEventListener('change', (e) => {
                this.loadRegencies(e.target.value);
            });
        }

        // auto-submit untuk radio buttons (dengan debounce)
        const radioButtons = this.form.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                this.debounce(() => this.form.submit(), 300);
            });
        });
    }

    async loadRegencies(provinceId) {
        if (!provinceId || !this.regencySelect) {
            return;
        }

        try {
            // tampilkan loading state
            this.regencySelect.disabled = true;
            this.regencySelect.innerHTML = '<option value="">Loading...</option>';

            const response = await fetch(`/api/regencies/${provinceId}`);
            const regencies = await response.json();

            // populate regency options
            let options = '<option value="">Semua Kabupaten/Kota</option>';
            regencies.forEach(regency => {
                options += `<option value="${regency.id}">${regency.name}</option>`;
            });

            this.regencySelect.innerHTML = options;
            this.regencySelect.disabled = false;

        } catch (error) {
            console.error('Error loading regencies:', error);
            this.regencySelect.innerHTML = '<option value="">Error loading data</option>';
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

/**
 * card animations dengan intersection observer
 */
class CardAnimations {
    constructor() {
        this.cards = document.querySelectorAll('.problem-card');
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        // stagger animation
                        setTimeout(() => {
                            entry.target.classList.add('fade-in-up');
                            entry.target.style.opacity = '1';
                        }, index * 50);
                        cardObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            this.cards.forEach(card => {
                card.style.opacity = '0';
                cardObserver.observe(card);
            });
        } else {
            // fallback: langsung tampilkan semua cards
            this.cards.forEach(card => {
                card.classList.add('fade-in-up');
            });
        }
    }
}

/**
 * wishlist handler (TODO: integrate dengan backend)
 */
class WishlistHandler {
    constructor() {
        this.init();
    }

    init() {
        document.addEventListener('click', (e) => {
            const wishlistBtn = e.target.closest('[data-wishlist]');
            if (wishlistBtn) {
                e.preventDefault();
                this.toggleWishlist(wishlistBtn);
            }
        });
    }

    async toggleWishlist(button) {
        const problemId = button.getAttribute('data-problem-id');
        
        try {
            // TODO: implementasi API call ke backend
            // const response = await fetch('/api/wishlist/toggle', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //     },
            //     body: JSON.stringify({ problem_id: problemId })
            // });

            // toggle UI state
            button.classList.toggle('active');
            const icon = button.querySelector('svg');
            if (button.classList.contains('active')) {
                icon.setAttribute('fill', 'currentColor');
                this.showNotification('Berhasil ditambahkan ke wishlist', 'success');
            } else {
                icon.setAttribute('fill', 'none');
                this.showNotification('Berhasil dihapus dari wishlist', 'info');
            }

        } catch (error) {
            console.error('Error toggling wishlist:', error);
            this.showNotification('Gagal memperbarui wishlist', 'error');
        }
    }

    showNotification(message, type) {
        // gunakan notification system yang sudah ada di app.blade.php
        if (window.notificationManager) {
            window.notificationManager.show(message, type);
        }
    }
}

/**
 * search autocomplete dengan debouncing
 */
class SearchAutocomplete {
    constructor() {
        this.searchInput = document.querySelector('input[name="search"]');
        this.resultsContainer = null;
        this.debounceTimer = null;
        this.init();
    }

    init() {
        if (!this.searchInput) return;

        // buat container untuk results
        this.createResultsContainer();

        // handle input dengan debounce
        this.searchInput.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.search(e.target.value);
            }, 300);
        });

        // close results saat click di luar
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-autocomplete')) {
                this.hideResults();
            }
        });
    }

    createResultsContainer() {
        const wrapper = this.searchInput.parentElement;
        wrapper.classList.add('search-autocomplete', 'relative');

        this.resultsContainer = document.createElement('div');
        this.resultsContainer.className = 'absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden z-50 max-h-96 overflow-y-auto';
        wrapper.appendChild(this.resultsContainer);
    }

    async search(query) {
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        try {
            // TODO: implementasi API call untuk autocomplete
            // const response = await fetch(`/api/problems/search?q=${encodeURIComponent(query)}&limit=5`);
            // const results = await response.json();
            // this.displayResults(results);

            // sementara hide results karena belum ada API
            this.hideResults();

        } catch (error) {
            console.error('Search error:', error);
            this.hideResults();
        }
    }

    displayResults(results) {
        if (results.length === 0) {
            this.hideResults();
            return;
        }

        let html = '<div class="py-2">';
        results.forEach(result => {
            html += `
                <a href="/student/problems/${result.id}" 
                   class="block px-4 py-2 hover:bg-gray-50 transition-colors">
                    <div class="font-medium text-gray-900">${result.title}</div>
                    <div class="text-sm text-gray-600">${result.institution_name}</div>
                </a>
            `;
        });
        html += '</div>';

        this.resultsContainer.innerHTML = html;
        this.resultsContainer.classList.remove('hidden');
    }

    hideResults() {
        if (this.resultsContainer) {
            this.resultsContainer.classList.add('hidden');
        }
    }
}

/**
 * initialize semua functionality saat DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {
    // init smooth scroll
    new SmoothScroll();
    
    // init lazy loading
    new LazyLoader();
    
    // init filter handler
    new FilterHandler();
    
    // init card animations
    new CardAnimations();
    
    // init wishlist handler
    new WishlistHandler();
    
    // init search autocomplete
    new SearchAutocomplete();

    // preload images untuk better UX
    preloadImages();
});

/**
 * preload images untuk smooth experience
 */
function preloadImages() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('loading' in HTMLImageElement.prototype) {
        // browser support native lazy loading
        return;
    }
    
    // fallback untuk browser yang tidak support
    images.forEach(img => {
        img.loading = 'eager';
    });
}

/**
 * performance monitoring (optional)
 */
if ('PerformanceObserver' in window) {
    const perfObserver = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
            if (entry.duration > 100) {
                console.warn('Slow operation detected:', entry.name, entry.duration + 'ms');
            }
        }
    });
    
    perfObserver.observe({ entryTypes: ['measure'] });
}

// export untuk digunakan di tempat lain
export {
    SmoothScroll,
    LazyLoader,
    FilterHandler,
    CardAnimations,
    WishlistHandler,
    SearchAutocomplete
};