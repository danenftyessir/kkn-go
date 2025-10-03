// resources/js/utils/wishlist.js
// utility functions untuk wishlist functionality

/**
 * toggle wishlist dengan smooth animation (vanilla JS approach)
 */
async function toggleWishlist(problemId, button) {
    // disable button sementara
    button.disabled = true;
    
    // add loading state
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;
    
    try {
        const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (data.success) {
            // update button state dengan animation
            updateButtonState(button, data.saved);
            
            // tampilkan notification
            showNotification(data.message || (data.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist'));
            
            // trigger custom event untuk update UI lain
            const event = new CustomEvent('wishlistUpdated', {
                detail: { problemId, saved: data.saved }
            });
            window.dispatchEvent(event);
        }
        
    } catch (error) {
        console.error('Error toggling wishlist:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        
        // restore original state
        button.innerHTML = originalHTML;
    } finally {
        button.disabled = false;
    }
}

/**
 * update button state dengan smooth animation
 */
function updateButtonState(button, isSaved) {
    // scale down animation
    button.style.transform = 'scale(0.8)';
    
    setTimeout(() => {
        // update classes
        if (isSaved) {
            button.classList.add('bg-red-50', 'border-red-300');
            button.classList.remove('bg-white', 'border-gray-300');
        } else {
            button.classList.add('bg-white', 'border-gray-300');
            button.classList.remove('bg-red-50', 'border-red-300');
        }
        
        // update icon
        const icon = button.querySelector('svg');
        if (icon) {
            icon.setAttribute('fill', isSaved ? 'currentColor' : 'none');
            icon.classList.toggle('text-red-600', isSaved);
            icon.classList.toggle('text-gray-600', !isSaved);
        }
        
        // scale up animation
        button.style.transform = 'scale(1.1)';
        
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    }, 150);
}

/**
 * tampilkan notification toast dengan smooth animation
 */
function showNotification(message, type = 'success') {
    // hapus notification yang ada
    const existingNotification = document.querySelector('.wishlist-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // buat notification element
    const notification = document.createElement('div');
    notification.className = `wishlist-notification fixed bottom-4 right-4 z-50 flex items-center space-x-3 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-gray-900 text-white' : 'bg-red-600 text-white'
    }`;
    
    notification.innerHTML = `
        <svg class="w-5 h-5 ${type === 'success' ? 'text-green-400' : 'text-white'}" fill="currentColor" viewBox="0 0 20 20">
            ${type === 'success' 
                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
            }
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // slide in animation
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // auto hide setelah 3 detik
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

/**
 * batch toggle wishlist untuk multiple items
 */
async function batchToggleWishlist(problemIds) {
    const results = [];
    
    for (const problemId of problemIds) {
        try {
            const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            results.push({ problemId, success: data.success, saved: data.saved });
        } catch (error) {
            results.push({ problemId, success: false, error: error.message });
        }
    }
    
    return results;
}

/**
 * check wishlist status untuk problem
 */
async function checkWishlistStatus(problemId) {
    try {
        const response = await fetch(`/student/wishlist/${problemId}/check`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        return data.saved || false;
    } catch (error) {
        console.error('Error checking wishlist status:', error);
        return false;
    }
}

/**
 * update wishlist count di UI
 */
function updateWishlistCount(count) {
    const countElements = document.querySelectorAll('[data-wishlist-count]');
    countElements.forEach(element => {
        element.textContent = count;
        
        // add bounce animation
        element.style.transform = 'scale(1.2)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    });
}

/**
 * listen untuk wishlist updates dan sync UI
 */
function initWishlistSync() {
    window.addEventListener('wishlistUpdated', (event) => {
        const { problemId, saved } = event.detail;
        
        // update semua wishlist buttons untuk problem ini
        const buttons = document.querySelectorAll(`[data-problem-id="${problemId}"]`);
        buttons.forEach(button => {
            updateButtonState(button, saved);
        });
    });
}

/**
 * TAMBAHAN: Alpine.js component untuk wishlist button
 * digunakan di blade dengan x-data="wishlistButton(problemId, initialSaved)"
 */
window.wishlistButton = function(problemId, initialSaved = false) {
    return {
        problemId: problemId,
        saved: initialSaved,
        loading: false,
        
        async toggle() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`/student/wishlist/${this.problemId}/toggle`, {
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
                    this.saved = data.saved;
                    showNotification(data.message);
                    
                    // trigger animation
                    if (this.saved) {
                        const button = this.$el.querySelector('button');
                        if (button) {
                            button.classList.add('animate-bounce');
                            setTimeout(() => {
                                button.classList.remove('animate-bounce');
                            }, 500);
                        }
                    }
                    
                    // dispatch event untuk sync
                    window.dispatchEvent(new CustomEvent('wishlistUpdated', {
                        detail: { problemId: this.problemId, saved: this.saved }
                    }));
                }
            } catch (error) {
                console.error('error toggle wishlist:', error);
                showNotification('terjadi kesalahan, silakan coba lagi', 'error');
            } finally {
                this.loading = false;
            }
        }
    };
};

// inisialisasi saat DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWishlistSync);
} else {
    initWishlistSync();
}

// export functions untuk digunakan di tempat lain
export {
    toggleWishlist,
    checkWishlistStatus,
    batchToggleWishlist,
    updateWishlistCount,
    showNotification
};
