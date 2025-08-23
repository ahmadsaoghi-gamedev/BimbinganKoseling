@props([
    'type' => 'info',
    'title' => '',
    'message' => '',
    'userRole' => 'admin',
    'dismissible' => true
])

@php
    $notificationTypes = [
        'success' => [
            'icon' => '🎉',
            'color' => 'text-green-600 dark:text-green-400',
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'border' => 'border-green-200 dark:border-green-800',
            'title' => 'Berhasil!',
            'messages' => [
                'siswa' => 'Kamu berhasil menyelesaikan langkah ini!',
                'gurubk' => 'Data berhasil disimpan dan diproses!',
                'admin' => 'Operasi berhasil dilaksanakan!',
                'orangtua' => 'Informasi berhasil diperbarui!'
            ]
        ],
        'error' => [
            'icon' => '😔',
            'color' => 'text-red-600 dark:text-red-400',
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800',
            'title' => 'Oops!',
            'messages' => [
                'siswa' => 'Jangan khawatir, coba lagi ya!',
                'gurubk' => 'Terjadi kesalahan, silakan coba lagi!',
                'admin' => 'Error terjadi, periksa kembali!',
                'orangtua' => 'Maaf, terjadi kesalahan!'
            ]
        ],
        'warning' => [
            'icon' => '⚠️',
            'color' => 'text-yellow-600 dark:text-yellow-400',
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'title' => 'Perhatian!',
            'messages' => [
                'siswa' => 'Perhatikan informasi ini ya!',
                'gurubk' => 'Perhatikan detail berikut!',
                'admin' => 'Perhatian diperlukan!',
                'orangtua' => 'Mohon perhatikan hal ini!'
            ]
        ],
        'info' => [
            'icon' => '💡',
            'color' => 'text-blue-600 dark:text-blue-400',
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'title' => 'Info!',
            'messages' => [
                'siswa' => 'Informasi penting untuk kamu!',
                'gurubk' => 'Informasi yang perlu diketahui!',
                'admin' => 'Informasi sistem!',
                'orangtua' => 'Informasi untuk Anda!'
            ]
        ]
    ];
    
    $notification = $notificationTypes[$type] ?? $notificationTypes['info'];
    $defaultMessage = $notification['messages'][$userRole] ?? $notification['messages']['admin'];
@endphp

<div x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     class="friendly-notification {{ $notification['bg'] }} {{ $notification['border'] }} border rounded-xl p-4 mb-4 shadow-lg">
    
    <div class="flex items-start space-x-3">
        <!-- Icon -->
        <div class="flex-shrink-0">
            <div class="w-10 h-10 {{ $notification['color'] }} bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm">
                <span class="text-xl">{{ $notification['icon'] }}</span>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold {{ $notification['color'] }} mb-1">
                    {{ $title ?: $notification['title'] }}
                </h3>
                
                @if($dismissible)
                <button @click="show = false" 
                        class="flex-shrink-0 ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                @endif
            </div>
            
            <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $message ?: $defaultMessage }}
            </p>
        </div>
    </div>

    <!-- Progress bar for auto-dismiss -->
    @if($dismissible)
    <div class="mt-3">
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
            <div class="notification-progress {{ $notification['color'] }} h-1 rounded-full transition-all duration-5000 ease-linear"
                 style="width: 0%"></div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss notification after 5 seconds
    const notifications = document.querySelectorAll('.friendly-notification');
    notifications.forEach(notification => {
        const progressBar = notification.querySelector('.notification-progress');
        if (progressBar) {
            let progress = 0;
            const interval = setInterval(() => {
                progress += 1;
                progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    clearInterval(interval);
                    const xData = notification.__x.$data;
                    if (xData && typeof xData.show !== 'undefined') {
                        xData.show = false;
                    }
                }
            }, 50); // 5000ms / 100 = 50ms per step
        }
    });
});
</script>

<style>
.friendly-notification {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-progress {
    background: linear-gradient(90deg, currentColor, currentColor);
    background-size: 200% 100%;
    animation: shimmer 2s ease-in-out infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
</style>
