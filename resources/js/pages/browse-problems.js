// resources/js/pages/browse-problems.js
// ajax filtering real-time untuk browse problems page

import { throttle, debounce } from 'lodash';

// state management untuk filters
const filterState = {
    search: '',
    province_id: '',
    regency_id: '',
    sdg: '',
    difficulty: '',
    duration: '',
    sort: 'latest',
    page: 1
};

// dom elements
let filterForm;
let problemsContainer;
let paginationContainer;
let loadingIndicator;

// inisialisasi saat dom ready
document.addEventListener('DOMContentLoaded', function() {
    initializeElements();
    attachEventListeners();
    initializeFromUrl();
});

// inisialisasi element references
function initializeElements() {
    filterForm = document.getElementById('filter-form');
    problemsContainer = document.getElementById('problems-container');
    paginationContainer = document.getElementById('pagination-container');
    
    // buat loading indicator
    createLoadingIndicator();
}

// buat loading indicator element
function createLoadingIndicator() {
    loadingIndicator = document.createElement('div');
    loadingIndicator.id = 'loading-indicator';
    loadingIndicator.className = 'hidden';
    loadingIndicator.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-600 mt-4">Memuat data...</p>
    `;
}

// attach event listeners
function attachEventListeners() {
    // search input dengan debounce
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            filterState.search = e.target.value;
            filterState.page = 1;
            applyFilters();
        }, 500));
    }
    
    // province change - load regencies
    const provinceSelect = document.getElementById('province-select');
    if (provinceSelect) {
        provinceSelect.addEventListener('change', function(e) {
            filterState.province_id = e.target.value;
            filterState.regency_id = '';
            filterState.page = 1;
            
            // load regencies untuk province yang dipilih
            if (e.target.value) {
                loadRegencies(e.target.value);
            } else {
                clearRegencies();
            }
            
            applyFilters();
        });
    }
    
    // regency change
    const regencySelect = document.getElementById('regency-select');
    if (regencySelect) {
        regencySelect.addEventListener('change', function(e) {
            filterState.regency_id = e.target.value;
            filterState.page = 1;
            applyFilters();
        });
    }
    
    // sdg filter
    const sdgSelect = document.getElementById('sdg-select');
    if (sdgSelect) {
        sdgSelect.addEventListener('change', function(e) {
            filterState.sdg = e.target.value;
            filterState.page = 1;
            applyFilters();
        });
    }
    
    // difficulty filter
    const difficultySelect = document.getElementById('difficulty-select');
    if (difficultySelect) {
        difficultySelect.addEventListener('change', function(e) {
            filterState.difficulty = e.target.value;
            filterState.page = 1;
            applyFilters();
        });
    }
    
    // duration filter
    const durationSelect = document.getElementById('duration-select');
    if (durationSelect) {
        durationSelect.addEventListener('change', function(e) {
            filterState.duration = e.target.value;
            filterState.page = 1;
            applyFilters();
        });
    }
    
    // sort change
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function(e) {
            filterState.sort = e.target.value;
            filterState.page = 1;
            applyFilters();
        });
    }
    
    // reset filters button
    const resetButton = document.getElementById('reset-filters');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            resetFilters();
        });
    }
}

// inisialisasi state dari url parameters
function initializeFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    
    filterState.search = urlParams.get('search') || '';
    filterState.province_id = urlParams.get('province_id') || '';
    filterState.regency_id = urlParams.get('regency_id') || '';
    filterState.sdg = urlParams.get('sdg') || '';
    filterState.difficulty = urlParams.get('difficulty') || '';
    filterState.duration = urlParams.get('duration') || '';
    filterState.sort = urlParams.get('sort') || 'latest';
    filterState.page = parseInt(urlParams.get('page')) || 1;
    
    // set form values
    setFormValues();
    
    // load regencies jika province sudah dipilih
    if (filterState.province_id) {
        loadRegencies(filterState.province_id, filterState.regency_id);
    }
}

// set form values dari state
function setFormValues() {
    const searchInput = document.getElementById('search-input');
    const provinceSelect = document.getElementById('province-select');
    const sdgSelect = document.getElementById('sdg-select');
    const difficultySelect = document.getElementById('difficulty-select');
    const durationSelect = document.getElementById('duration-select');
    const sortSelect = document.getElementById('sort-select');
    
    if (searchInput) searchInput.value = filterState.search;
    if (provinceSelect) provinceSelect.value = filterState.province_id;
    if (sdgSelect) sdgSelect.value = filterState.sdg;
    if (difficultySelect) difficultySelect.value = filterState.difficulty;
    if (durationSelect) durationSelect.value = filterState.duration;
    if (sortSelect) sortSelect.value = filterState.sort;
}

// load regencies berdasarkan province
async function loadRegencies(provinceId, selectedRegencyId = null) {
    const regencySelect = document.getElementById('regency-select');
    if (!regencySelect) return;
    
    // tampilkan loading state
    regencySelect.disabled = true;
    regencySelect.innerHTML = '<option value="">Memuat...</option>';
    
    try {
        const response = await fetch(`/api/regencies/${provinceId}`);
        const regencies = await response.json();
        
        // populate regency options
        regencySelect.innerHTML = '<option value="">Semua Kabupaten/Kota</option>';
        regencies.forEach(regency => {
            const option = document.createElement('option');
            option.value = regency.id;
            option.textContent = regency.name;
            if (selectedRegencyId && regency.id == selectedRegencyId) {
                option.selected = true;
            }
            regencySelect.appendChild(option);
        });
        
        regencySelect.disabled = false;
    } catch (error) {
        console.error('Error loading regencies:', error);
        regencySelect.innerHTML = '<option value="">Gagal memuat kabupaten</option>';
        regencySelect.disabled = false;
    }
}

// clear regencies dropdown
function clearRegencies() {
    const regencySelect = document.getElementById('regency-select');
    if (!regencySelect) return;
    
    regencySelect.innerHTML = '<option value="">Pilih Provinsi Dulu</option>';
    regencySelect.disabled = true;
}

// apply filters dengan ajax
async function applyFilters() {
    showLoading();
    
    // build query params
    const queryParams = new URLSearchParams();
    
    Object.keys(filterState).forEach(key => {
        if (filterState[key]) {
            queryParams.append(key, filterState[key]);
        }
    });
    
    // update url tanpa reload
    const newUrl = `${window.location.pathname}?${queryParams.toString()}`;
    window.history.pushState({}, '', newUrl);
    
    try {
        const response = await fetch(`/student/browse-problems?${queryParams.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        
        // update dom dengan smooth transition
        await updateProblemsContainer(data.html);
        updatePagination(data.pagination);
        updateStats(data.stats);
        
        // scroll ke top dengan smooth behavior
        smoothScrollToTop();
        
    } catch (error) {
        console.error('Error applying filters:', error);
        showError('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
    } finally {
        hideLoading();
    }
}

// update problems container dengan fade animation
async function updateProblemsContainer(html) {
    if (!problemsContainer) return;
    
    // fade out
    problemsContainer.style.transition = 'opacity 0.3s ease';
    problemsContainer.style.opacity = '0';
    
    await new Promise(resolve => setTimeout(resolve, 300));
    
    // update content
    problemsContainer.innerHTML = html;
    
    // fade in
    problemsContainer.style.opacity = '1';
    
    // trigger entrance animations
    const cards = problemsContainer.querySelectorAll('.problem-card');
    cards.forEach((card, index) => {
        card.style.animation = 'none';
        card.offsetHeight; // trigger reflow
        card.style.animation = `fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) ${index * 0.05}s forwards`;
    });
}

// update pagination
function updatePagination(paginationHtml) {
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = paginationHtml;
    
    // attach click handlers untuk pagination links
    const paginationLinks = paginationContainer.querySelectorAll('a[data-page]');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            filterState.page = parseInt(this.dataset.page);
            applyFilters();
        });
    });
}

// update stats
function updateStats(stats) {
    if (!stats) return;
    
    const totalProblemsEl = document.getElementById('total-problems-stat');
    const totalSlotsEl = document.getElementById('total-slots-stat');
    const urgentCountEl = document.getElementById('urgent-count-stat');
    
    if (totalProblemsEl) {
        animateNumber(totalProblemsEl, stats.total_problems);
    }
    if (totalSlotsEl) {
        animateNumber(totalSlotsEl, stats.total_slots);
    }
    if (urgentCountEl) {
        animateNumber(urgentCountEl, stats.urgent_count);
    }
}

// animate number counting
function animateNumber(element, targetNumber) {
    const currentNumber = parseInt(element.textContent) || 0;
    const duration = 500; // ms
    const steps = 30;
    const increment = (targetNumber - currentNumber) / steps;
    let current = currentNumber;
    let step = 0;
    
    const timer = setInterval(() => {
        step++;
        current += increment;
        element.textContent = Math.round(current);
        
        if (step >= steps) {
            element.textContent = targetNumber;
            clearInterval(timer);
        }
    }, duration / steps);
}

// smooth scroll ke top
function smoothScrollToTop() {
    const targetPosition = problemsContainer.offsetTop - 100;
    window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
    });
}

// show loading indicator
function showLoading() {
    if (!problemsContainer || !loadingIndicator) return;
    
    // insert loading indicator
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
    // TODO: implementasi toast notification yang lebih baik
    alert(message);
}

// reset semua filters
function resetFilters() {
    filterState.search = '';
    filterState.province_id = '';
    filterState.regency_id = '';
    filterState.sdg = '';
    filterState.difficulty = '';
    filterState.duration = '';
    filterState.sort = 'latest';
    filterState.page = 1;
    
    setFormValues();
    clearRegencies();
    applyFilters();
}

// throttle scroll event untuk performance
const handleScroll = throttle(function() {
    // TODO: implementasi infinite scroll jika diperlukan
    // atau lazy loading untuk images
}, 200);

window.addEventListener('scroll', handleScroll);

// export untuk digunakan di tempat lain jika diperlukan
export {
    filterState,
    applyFilters,
    loadRegencies,
    resetFilters
};