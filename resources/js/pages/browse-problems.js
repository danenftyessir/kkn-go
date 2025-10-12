/**
 * browse problems page - filter, search, dan pagination
 * smooth scrolling dan loading states
 */

// state management untuk filters
let filterState = {
    search: '',
    province_id: '',
    regency_id: '',
    sdg: '',
    difficulty: '',
    duration: '',
    sort: 'latest',
    page: 1
};

// debounce helper untuk search
function debounce(func, wait) {
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

// throttle helper untuk scroll
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// dom elements
const searchInput = document.getElementById('search');
const provinceSelect = document.getElementById('province_id');
const regencySelect = document.getElementById('regency_id');
const sdgSelect = document.getElementById('sdg');
const difficultySelect = document.getElementById('difficulty');
const durationSelect = document.getElementById('duration');
const sortSelect = document.getElementById('sort');
const problemsContainer = document.getElementById('problems-container');
const loadingIndicator = document.getElementById('loading-indicator');
const resetButton = document.getElementById('reset-filters');

// initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeSearch();
    initializeProvinceChange();
    initializeResetButton();
    initializeLazyLoading();
});

// initialize filter dropdowns
function initializeFilters() {
    if (provinceSelect) {
        provinceSelect.addEventListener('change', handleProvinceChange);
    }
    
    if (regencySelect) {
        regencySelect.addEventListener('change', () => {
            filterState.regency_id = regencySelect.value;
            applyFilters();
        });
    }
    
    if (sdgSelect) {
        sdgSelect.addEventListener('change', () => {
            filterState.sdg = sdgSelect.value;
            applyFilters();
        });
    }
    
    if (difficultySelect) {
        difficultySelect.addEventListener('change', () => {
            filterState.difficulty = difficultySelect.value;
            applyFilters();
        });
    }
    
    if (durationSelect) {
        durationSelect.addEventListener('change', () => {
            filterState.duration = durationSelect.value;
            applyFilters();
        });
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            filterState.sort = sortSelect.value;
            applyFilters();
        });
    }
}

// initialize search dengan debounce
function initializeSearch() {
    if (searchInput) {
        const debouncedSearch = debounce(() => {
            filterState.search = searchInput.value;
            filterState.page = 1;
            applyFilters();
        }, 500);
        
        searchInput.addEventListener('input', debouncedSearch);
    }
}

// initialize province change handler
function initializeProvinceChange() {
    if (provinceSelect) {
        provinceSelect.addEventListener('change', handleProvinceChange);
    }
}

// initialize reset button
function initializeResetButton() {
    if (resetButton) {
        resetButton.addEventListener('click', resetFilters);
    }
}

// handle province change - load regencies
async function handleProvinceChange() {
    const provinceId = provinceSelect.value;
    filterState.province_id = provinceId;
    filterState.regency_id = '';
    
    if (provinceId) {
        await loadRegencies(provinceId);
    } else {
        clearRegencies();
    }
    
    applyFilters();
}

// load regencies berdasarkan province
async function loadRegencies(provinceId) {
    if (!regencySelect) return;
    
    regencySelect.disabled = true;
    regencySelect.innerHTML = '<option value="">Memuat...</option>';
    
    try {
        const response = await fetch(`/api/regencies/${provinceId}`);
        const data = await response.json();
        
        clearRegencies();
        
        if (data.regencies && data.regencies.length > 0) {
            data.regencies.forEach(regency => {
                const option = document.createElement('option');
                option.value = regency.id;
                option.textContent = regency.name;
                regencySelect.appendChild(option);
            });
            regencySelect.disabled = false;
        } else {
            regencySelect.innerHTML = '<option value="">Tidak ada kabupaten/kota</option>';
        }
    } catch (error) {
        console.error('error loading regencies:', error);
        regencySelect.innerHTML = '<option value="">Gagal memuat</option>';
    }
}

// clear regencies dropdown
function clearRegencies() {
    if (!regencySelect) return;
    
    regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    regencySelect.disabled = true;
}

// apply filters - kirim ke server
async function applyFilters() {
    showLoading();
    
    try {
        const params = new URLSearchParams();
        Object.keys(filterState).forEach(key => {
            if (filterState[key]) {
                params.append(key, filterState[key]);
            }
        });
        
        const response = await fetch(`/student/browse-problems?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('gagal memuat data');
        }
        
        const html = await response.text();
        
        // update content
        if (problemsContainer) {
            problemsContainer.innerHTML = html;
            
            // scroll to top dengan smooth animation
            smoothScrollTo(0);
            
            // reinitialize lazy loading untuk images baru
            initializeLazyLoading();
        }
        
    } catch (error) {
        console.error('error applying filters:', error);
        showError('Gagal memuat data. Silakan coba lagi.');
    } finally {
        hideLoading();
    }
}

// reset semua filters
function resetFilters() {
    filterState = {
        search: '',
        province_id: '',
        regency_id: '',
        sdg: '',
        difficulty: '',
        duration: '',
        sort: 'latest',
        page: 1
    };
    
    // reset form values
    if (searchInput) searchInput.value = '';
    if (provinceSelect) provinceSelect.value = '';
    if (regencySelect) regencySelect.value = '';
    if (sdgSelect) sdgSelect.value = '';
    if (difficultySelect) difficultySelect.value = '';
    if (durationSelect) durationSelect.value = '';
    if (sortSelect) sortSelect.value = 'latest';
    
    clearRegencies();
    applyFilters();
}

// show loading indicator
function showLoading() {
    if (!problemsContainer || !loadingIndicator) return;
    
    problemsContainer.style.opacity = '0.5';
    problemsContainer.style.pointerEvents = 'none';
    
    if (!document.getElementById('loading-indicator')) {
        problemsContainer.parentNode.insertBefore(loadingIndicator, problemsContainer);
    }
    
    loadingIndicator.classList.remove('hidden');
}

// hide loading indicator
function hideLoading() {
    if (!problemsContainer || !loadingIndicator) return;
    
    problemsContainer.style.opacity = '1';
    problemsContainer.style.pointerEvents = 'auto';
    loadingIndicator.classList.add('hidden');
}

// show error message
function showError(message) {
    // gunakan notification manager global jika ada
    if (window.notifications) {
        window.notifications.show(message, 'error');
        return;
    }
    
    // fallback: implementasi toast notification sederhana
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// smooth scroll dengan easing
function smoothScrollTo(target, duration = 800) {
    const start = window.pageYOffset;
    const distance = target - start;
    const startTime = performance.now();
    
    function easeInOutCubic(t) {
        return t < 0.5 
            ? 4 * t * t * t 
            : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }
    
    function animation(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = easeInOutCubic(progress);
        
        window.scrollTo(0, start + distance * ease);
        
        if (progress < 1) {
            requestAnimationFrame(animation);
        }
    }
    
    requestAnimationFrame(animation);
}

// lazy loading untuk images
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.add('fade-in');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // fallback untuk browser tanpa intersection observer
        images.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
}

// throttle scroll event untuk performance
const handleScroll = throttle(function() {
    // tambahkan shadow ke header saat scroll
    const header = document.querySelector('.filter-header');
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
    }
}, 200);

window.addEventListener('scroll', handleScroll);

// handle browser back/forward
window.addEventListener('popstate', function() {
    location.reload(); // simple approach: reload page
});

// export untuk digunakan di tempat lain jika diperlukan
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        filterState,
        applyFilters,
        loadRegencies,
        resetFilters
    };
}