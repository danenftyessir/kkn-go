/**
 * resources/js/app.js
 * main javascript file untuk aplikasi
 */

import './bootstrap';

/**
 * notification helper
 * untuk menampilkan flash messages dari session
 */
class NotificationManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        this.createContainer();
        this.showFlashMessages();
    }

    createContainer() {
        if (document.getElementById('notification-container')) return;

        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'fixed top-20 right-4 z-[9998] space-y-2 max-w-sm';
        document.body.appendChild(this.container);
    }

    show(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800'
        };

        const icons = {
            success: '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            error: '<svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            info: '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
            warning: '<svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
        };

        notification.className = `${colors[type]} border-l-4 rounded-lg p-4 shadow-lg transform transition-all duration-300 opacity-0 translate-x-full flex items-start gap-3`;
        notification.innerHTML = `
            ${icons[type]}
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        `;

        this.container.appendChild(notification);

        // trigger animation
        setTimeout(() => {
            notification.classList.remove('opacity-0', 'translate-x-full');
        }, 10);

        // auto remove
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }
    }

    showFlashMessages() {
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

// inisialisasi notification manager
const notifications = new NotificationManager();
window.notifications = notifications;

/**
 * fungsi wishlist global
 * untuk toggle wishlist dari browse problems page
 */
window.toggleWishlist = async function(problemId, button) {
    // disable button sementara
    button.disabled = true;
    const originalHTML = button.innerHTML;
    
    // tampilkan loading
    button.innerHTML = `
        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;
    
    try {
        const response = await fetch(`/student/wishlist/${problemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('gagal toggle wishlist');
        }
        
        const data = await response.json();
        
        if (data.success) {
            // update button state
            const svg = button.querySelector('svg');
            const isWishlisted = data.saved;
            
            // restore button content
            button.innerHTML = originalHTML;
            
            // update style
            button.setAttribute('data-wishlisted', isWishlisted ? 'true' : 'false');
            
            const newSvg = button.querySelector('svg');
            if (newSvg) {
                newSvg.setAttribute('fill', isWishlisted ? 'currentColor' : 'none');
                newSvg.classList.toggle('fill-red-500', isWishlisted);
                newSvg.classList.toggle('text-red-500', isWishlisted);
                newSvg.classList.toggle('text-gray-600', !isWishlisted);
            }
            
            // add animation
            button.style.transform = 'scale(1.2)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
            
            // tampilkan notifikasi
            notifications.show(
                data.message || (isWishlisted ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'),
                'success',
                3000
            );
            
            // dispatch event untuk update UI lain
            window.dispatchEvent(new CustomEvent('wishlistUpdated', {
                detail: { problemId, saved: isWishlisted }
            }));
        }
    } catch (error) {
        console.error('error toggle wishlist:', error);
        
        // restore button
        button.innerHTML = originalHTML;
        
        notifications.show('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        button.disabled = false;
    }
};

/**
 * smooth scroll to top button
 */
document.addEventListener('DOMContentLoaded', function() {
    const scrollBtn = document.getElementById('scroll-to-top');
    
    if (scrollBtn) {
        // tampilkan button saat scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.classList.remove('hidden');
            } else {
                scrollBtn.classList.add('hidden');
            }
        });
        
        // scroll to top dengan smooth animation
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});