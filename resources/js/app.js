import './bootstrap';
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';

// initialize alpine.js
window.Alpine = Alpine;
Alpine.start();

// initialize AOS (animate on scroll)
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 600,
        easing: 'ease-out',
        once: true,
        offset: 50,
    });
});

// smooth scroll manager menggunakan prinsip OOP
class SmoothScrollManager {
    constructor() {
        this.scrollBehavior = 'smooth';
        this.init();
    }

    init() {
        this.setupSmoothScrolling();
        this.setupScrollToTop();
        this.setupAnchorLinks();
    }

    setupSmoothScrolling() {
        document.documentElement.style.scrollBehavior = this.scrollBehavior;
        
        // tambahkan GPU acceleration
        document.body.style.transform = 'translateZ(0)';
        document.body.style.backfaceVisibility = 'hidden';
    }

    setupScrollToTop() {
        const scrollBtn = document.getElementById('scroll-to-top');
        if (!scrollBtn) return;

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.remove('hidden');
            } else {
                scrollBtn.classList.add('hidden');
            }
        }, { passive: true });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    setupAnchorLinks() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (href === '#') return;

                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// page transition manager
class PageTransitionManager {
    constructor() {
        this.duration = 300;
        this.init();
    }

    init() {
        this.setupPageTransitions();
        this.setupFormTransitions();
    }

    setupPageTransitions() {
        // tambahkan class untuk animasi fade in saat page load
        document.body.classList.add('page-transition');
        
        // smooth navigation untuk internal links
        document.querySelectorAll('a:not([target="_blank"])').forEach(link => {
            if (link.hostname === window.location.hostname) {
                link.addEventListener('click', (e) => {
                    // skip jika ada # atau javascript:
                    if (link.getAttribute('href')?.startsWith('#') || 
                        link.getAttribute('href')?.startsWith('javascript:')) {
                        return;
                    }

                    // fade out sebelum navigate
                    document.body.style.opacity = '0.8';
                    document.body.style.transition = `opacity ${this.duration}ms ease-out`;
                });
            }
        });
    }

    setupFormTransitions() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner mr-2"></span> Memproses...';
                }
            });
        });
    }
}

// notification manager
class NotificationManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        this.createContainer();
        this.showExistingNotifications();
    }

    createContainer() {
        if (document.getElementById('notification-container')) return;

        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(this.container);
    }

    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} animate-slideInRight shadow-lg max-w-sm`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="ml-4 text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        this.container.appendChild(notification);

        // auto remove setelah duration
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('animate-fadeOut');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }
    }

    showExistingNotifications() {
        // ambil flash messages dari Laravel session
        const successMsg = document.querySelector('[data-success-message]');
        const errorMsg = document.querySelector('[data-error-message]');
        const infoMsg = document.querySelector('[data-info-message]');
        const warningMsg = document.querySelector('[data-warning-message]');

        if (successMsg) this.show(successMsg.dataset.successMessage, 'success');
        if (errorMsg) this.show(errorMsg.dataset.errorMessage, 'error');
        if (infoMsg) this.show(infoMsg.dataset.infoMessage, 'info');
        if (warningMsg) this.show(warningMsg.dataset.warningMessage, 'warning');
    }
}

// initialize managers saat DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new SmoothScrollManager();
    new PageTransitionManager();
    window.notificationManager = new NotificationManager();
});

// export untuk digunakan di file lain
export { SmoothScrollManager, PageTransitionManager, NotificationManager };