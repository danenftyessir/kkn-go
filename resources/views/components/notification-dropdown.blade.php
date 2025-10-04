{{-- notification dropdown component untuk navbar --}}
<div x-data="notificationDropdown()" @click.away="open = false" class="relative">
    {{-- notification bell button --}}
    <button @click="toggleDropdown()" 
            type="button"
            class="relative p-2 text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition-all duration-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        {{-- unread badge --}}
        <span x-show="unreadCount > 0" 
              x-transition
              class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </span>
    </button>

    {{-- dropdown panel --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
        
        {{-- header --}}
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
            <div class="flex items-center gap-2">
                <button @click="markAllAsRead()" 
                        x-show="unreadCount > 0"
                        class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>

        {{-- notifications list --}}
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div @click="handleNotificationClick(notification)"
                     class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer border-b border-gray-100 last:border-0"
                     :class="!notification.is_read ? 'bg-blue-50' : ''">
                    <div class="flex items-start gap-3">
                        {{-- icon berdasarkan tipe --}}
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-xl"
                             :class="`bg-${getNotificationColor(notification.type)}-100`">
                            <span x-text="getNotificationIcon(notification.type)"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-sm font-semibold text-gray-900 truncate" x-text="notification.title"></h4>
                                <span x-show="!notification.is_read" 
                                      class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full"></span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="getTimeAgo(notification.created_at)"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- footer --}}
        <div class="px-4 py-3 border-t border-gray-200">
            <a href="{{ route('notifications.index') }}" 
               class="block text-center text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function notificationDropdown() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,

        async init() {
            await this.loadNotifications();
            // refresh setiap 30 detik
            setInterval(() => this.loadNotifications(), 30000);
        },

        async toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                await this.loadNotifications();
            }
        },

        async loadNotifications() {
            try {
                const response = await fetch('{{ route("notifications.latest") }}');
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('gagal load notifikasi:', error);
            }
        },

        async handleNotificationClick(notification) {
            // tandai sebagai dibaca
            if (!notification.is_read) {
                try {
                    await fetch(`/notifications/${notification.id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                } catch (error) {
                    console.error('gagal mark as read:', error);
                }
            }

            // redirect ke action url
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                await this.loadNotifications();
            } catch (error) {
                console.error('gagal mark all as read:', error);
            }
        },

        getNotificationIcon(type) {
            const icons = {
                'application_submitted': '📝',
                'application_accepted': '✅',
                'application_rejected': '❌',
                'project_started': '🚀',
                'project_milestone': '🎯',
                'report_submitted': '📄',
                'report_approved': '✔️',
                'report_rejected': '✖️',
                'review_received': '⭐',
                'problem_published': '📢',
                'problem_closed': '🔒',
                'message_received': '💬',
                'deadline_reminder': '⏰',
                'general': 'ℹ️',
            };
            return icons[type] || 'ℹ️';
        },

        getNotificationColor(type) {
            const colors = {
                'application_submitted': 'blue',
                'application_accepted': 'green',
                'application_rejected': 'red',
                'project_started': 'purple',
                'project_milestone': 'indigo',
                'report_submitted': 'yellow',
                'report_approved': 'green',
                'report_rejected': 'red',
                'review_received': 'yellow',
                'problem_published': 'blue',
                'problem_closed': 'gray',
                'message_received': 'pink',
                'deadline_reminder': 'orange',
                'general': 'gray',
            };
            return colors[type] || 'gray';
        },

        getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            const intervals = {
                tahun: 31536000,
                bulan: 2592000,
                minggu: 604800,
                hari: 86400,
                jam: 3600,
                menit: 60
            };

            for (const [name, value] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / value);
                if (interval >= 1) {
                    return `${interval} ${name} yang lalu`;
                }
            }

            return 'Baru saja';
        }
    }
}
</script>
@endpush