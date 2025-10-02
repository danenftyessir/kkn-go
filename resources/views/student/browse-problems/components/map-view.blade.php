{{-- resources/views/student/browse-problems/components/map-view.blade.php --}}
{{-- component untuk menampilkan problems di peta menggunakan leaflet --}}

<div class="map-container bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
     x-data="problemsMap()"
     x-init="initMap()">
    
    <!-- map controls -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button @click="showMap = !showMap" 
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span x-text="showMap ? 'Sembunyikan Peta' : 'Tampilkan Peta'"></span>
                </button>

                <div x-show="showMap" class="text-sm text-gray-600">
                    <span class="font-semibold" x-text="markerCount"></span> lokasi proyek ditampilkan
                </div>
            </div>

            <!-- legend -->
            <div x-show="showMap" class="flex items-center space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                    <span class="text-gray-600">Open</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                    <span class="text-gray-600">Urgent</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                    <span class="text-gray-600">Featured</span>
                </div>
            </div>
        </div>
    </div>

    <!-- map canvas -->
    <div x-show="showMap" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="relative">
        <div id="problems-map" class="w-full" style="height: 600px;"></div>
        
        <!-- loading overlay -->
        <div x-show="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="text-center">
                <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-600">Memuat peta...</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* leaflet custom styles */
.leaflet-container {
    font-family: inherit;
}

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.custom-popup .leaflet-popup-content {
    margin: 12px;
    min-width: 250px;
}

.custom-popup .leaflet-popup-tip {
    box-shadow: 0 3px 14px rgba(0, 0, 0, 0.1);
}

/* marker cluster custom styles */
.marker-cluster-small {
    background-color: rgba(59, 130, 246, 0.6);
}

.marker-cluster-small div {
    background-color: rgba(59, 130, 246, 0.8);
    color: white;
    font-weight: bold;
}

.marker-cluster-medium {
    background-color: rgba(16, 185, 129, 0.6);
}

.marker-cluster-medium div {
    background-color: rgba(16, 185, 129, 0.8);
    color: white;
    font-weight: bold;
}

.marker-cluster-large {
    background-color: rgba(239, 68, 68, 0.6);
}

.marker-cluster-large div {
    background-color: rgba(239, 68, 68, 0.8);
    color: white;
    font-weight: bold;
}

/* smooth zoom animation */
.leaflet-container {
    transition: opacity 0.3s ease;
}

/* map card hover effect */
.map-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.map-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@push('scripts')
<!-- leaflet CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<!-- marker cluster plugin -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.min.js"></script>

<script>
// alpine.js component untuk problems map
function problemsMap() {
    return {
        map: null,
        markers: null,
        showMap: false,
        loading: true,
        markerCount: 0,
        
        // data problems dari backend
        problems: @json($problems->items()),
        
        initMap() {
            // tunggu sampai map container terlihat
            this.$watch('showMap', value => {
                if (value && !this.map) {
                    // timeout untuk transisi
                    setTimeout(() => {
                        this.createMap();
                    }, 100);
                }
            });
        },
        
        createMap() {
            this.loading = true;
            
            // inisialisasi map centered di Indonesia
            this.map = L.map('problems-map', {
                center: [-2.5, 118], // center Indonesia
                zoom: 5,
                scrollWheelZoom: true,
                zoomControl: true,
                attributionControl: true
            });
            
            // tambahkan tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 18,
                minZoom: 5
            }).addTo(this.map);
            
            // inisialisasi marker cluster
            this.markers = L.markerClusterGroup({
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                maxClusterRadius: 50,
                iconCreateFunction: function(cluster) {
                    const count = cluster.getChildCount();
                    let className = 'marker-cluster-';
                    if (count < 10) {
                        className += 'small';
                    } else if (count < 50) {
                        className += 'medium';
                    } else {
                        className += 'large';
                    }
                    return L.divIcon({
                        html: '<div><span>' + count + '</span></div>',
                        className: 'marker-cluster ' + className,
                        iconSize: new L.Point(40, 40)
                    });
                }
            });
            
            // tambahkan markers untuk setiap problem
            this.addMarkers();
            
            // tambahkan markers ke map
            this.map.addLayer(this.markers);
            
            // fit bounds jika ada markers
            if (this.markerCount > 0) {
                this.map.fitBounds(this.markers.getBounds(), {
                    padding: [50, 50]
                });
            }
            
            this.loading = false;
            
            // fix untuk leaflet tiles tidak muncul
            setTimeout(() => {
                this.map.invalidateSize();
            }, 200);
        },
        
        addMarkers() {
            this.markerCount = 0;
            
            this.problems.forEach(problem => {
                // TODO: koordinat perlu ditambahkan di database atau geocoding API
                // untuk demo, gunakan koordinat acak di Indonesia
                const lat = this.getRandomLatIndonesia();
                const lng = this.getRandomLngIndonesia();
                
                // tentukan warna marker berdasarkan status
                let markerColor = 'green'; // open
                if (problem.is_urgent) {
                    markerColor = 'red';
                } else if (problem.is_featured) {
                    markerColor = 'blue';
                }
                
                // custom icon
                const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${markerColor}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });
                
                // buat marker
                const marker = L.marker([lat, lng], { icon: customIcon });
                
                // popup content
                const popupContent = this.createPopupContent(problem);
                marker.bindPopup(popupContent, {
                    className: 'custom-popup',
                    maxWidth: 300
                });
                
                // tambahkan ke cluster
                this.markers.addLayer(marker);
                this.markerCount++;
            });
        },
        
        createPopupContent(problem) {
            const deadline = new Date(problem.application_deadline);
            const now = new Date();
            const daysLeft = Math.ceil((deadline - now) / (1000 * 60 * 60 * 24));
            
            return `
                <div class="map-card">
                    <h3 class="font-bold text-gray-900 mb-2 text-sm line-clamp-2">${problem.title}</h3>
                    <p class="text-xs text-gray-600 mb-2">${problem.institution.name}</p>
                    
                    <div class="space-y-1 mb-3">
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            ${problem.regency.name}
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            ${problem.required_students} mahasiswa
                        </div>
                        <div class="flex items-center text-xs ${daysLeft <= 7 ? 'text-red-600 font-semibold' : 'text-gray-600'}">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${daysLeft} hari lagi
                        </div>
                    </div>
                    
                    <a href="/student/problems/${problem.id}" 
                       class="block w-full px-3 py-2 bg-blue-600 text-white text-xs font-semibold text-center rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            `;
        },
        
        // helper untuk generate koordinat acak di Indonesia
        // TODO: ganti dengan koordinat real dari database atau geocoding
        getRandomLatIndonesia() {
            // Indonesia latitude range: -11 to 6
            return -11 + Math.random() * 17;
        },
        
        getRandomLngIndonesia() {
            // Indonesia longitude range: 95 to 141
            return 95 + Math.random() * 46;
        }
    };
}
</script>
@endpush