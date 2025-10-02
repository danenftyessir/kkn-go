/**
 * wishlist.js
 * handle wishlist/save functionality untuk problems
 * 
 * usage: letakkan di resources/js/utils/wishlist.js
 */

/**
 * toggle wishlist status untuk problem
 * @param {number} problemId - ID problem yang akan di-save/unsave
 * @returns {Promise<object>} response dari server
 */
export async function toggleWishlist(problemId) {
    try {
        const response = await fetch(`/student/wishlist/${problemId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('gagal toggle wishlist');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('error toggle wishlist:', error);
        throw error;
    }
}

/**
 * cek apakah problem sudah di-save
 * @param {number} problemId - ID problem
 * @returns {Promise<boolean>} true jika sudah disave
 */
export async function checkWishlistStatus(problemId) {
    try {
        const response = await fetch(`/student/wishlist/${problemId}/check`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        });

        if (!response.ok) {
            throw new Error('gagal cek wishlist status');
        }

        const data = await response.json();
        return data.saved;
    } catch (error) {
        console.error('error check wishlist:', error);
        return false;
    }
}

/**
 * update notes di wishlist
 * @param {number} problemId - ID problem
 * @param {string} notes - catatan yang akan disimpan
 * @returns {Promise<object>} response dari server
 */
export async function updateWishlistNotes(problemId, notes) {
    try {
        const response = await fetch(`/student/wishlist/${problemId}/notes`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notes })
        });

        if (!response.ok) {
            throw new Error('gagal update notes');
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('error update notes:', error);
        throw error;
    }
}

/**
 * get CSRF token dari meta tag
 * @returns {string} CSRF token
 */
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.error('CSRF token tidak ditemukan');
        return '';
    }
    return token.content;
}

/**
 * alpine.js component untuk wishlist button
 * usage: x-data="wishlistButton(problemId, initialSaved)"
 * 
 * @param {number} problemId - ID problem
 * @param {boolean} initialSaved - status awal (sudah disave atau belum)
 * @returns {object} alpine component
 */
export function wishlistButton(problemId, initialSaved = false) {
    return {
        problemId: problemId,
        saved: initialSaved,
        loading: false,
        
        async init() {
            // cek status wishlist saat init
            if (!this.saved) {
                this.saved = await checkWishlistStatus(this.problemId);
            }
        },
        
        async toggle() {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const result = await toggleWishlist(this.problemId);
                
                if (result.success) {
                    this.saved = result.saved;
                    
                    // tampilkan notifikasi
                    this.showNotification(result.message);
                    
                    // animasi
                    this.animateButton();
                }
            } catch (error) {
                this.showNotification('terjadi kesalahan, silakan coba lagi', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        animateButton() {
            // animasi heart beat saat save
            const button = this.$el;
            if (this.saved) {
                button.classList.add('animate-heart-beat');
                setTimeout(() => {
                    button.classList.remove('animate-heart-beat');
                }, 1000);
            }
        },
        
        showNotification(message, type = 'success') {
            // buat notifikasi toast sederhana
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.textContent = message;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            
            document.body.appendChild(toast);
            
            // animasi masuk
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
            
            // hapus setelah 3 detik
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        },
        
        getButtonClass() {
            if (this.saved) {
                return 'bg-red-500 text-white hover:bg-red-600';
            }
            return 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300';
        },
        
        getIconClass() {
            return this.saved ? 'fill-current' : 'fill-none';
        }
    };
}

/**
 * helper untuk menambahkan CSS animation untuk heart beat
 * panggil di main JS atau di component yang menggunakan wishlist
 */
export function addWishlistStyles() {
    if (document.getElementById('wishlist-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'wishlist-styles';
    style.textContent = `
        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            10%, 30% { transform: scale(0.9); }
            20%, 40%, 60%, 80% { transform: scale(1.1); }
            50%, 70% { transform: scale(1.05); }
        }
        
        .animate-heart-beat {
            animation: heartBeat 1s ease-in-out;
        }
        
        .wishlist-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .wishlist-btn:hover {
            transform: scale(1.05);
        }
        
        .wishlist-btn:active {
            transform: scale(0.95);
        }
        
        .wishlist-icon {
            transition: all 0.3s ease;
        }
    `;
    
    document.head.appendChild(style);
}

// auto-add styles saat module di-import
if (typeof document !== 'undefined') {
    addWishlistStyles();
}