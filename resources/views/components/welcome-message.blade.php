@props(['userRole' => null, 'userName' => ''])

@php
    $welcomeMessages = [
        'siswa' => [
            'title' => 'Selamat datang di Portal BK! 👋',
            'subtitle' => 'Tempat aman untuk curhat dan konsultasi',
            'description' => 'Jangan ragu untuk berbicara dengan Guru BK. Kami di sini untuk membantu kamu!',
            'tips' => [
                '💡 Tips: Curhat rahasia akan dijaga kerahasiaannya',
                '🤝 Konsultasi dengan Guru BK untuk solusi terbaik',
                '📝 Isi formulir cek masalah untuk identifikasi awal'
            ],
            'color' => 'text-red-600 dark:text-red-400',
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800'
        ],
        'gurubk' => [
            'title' => 'Selamat datang, Guru BK! 🎓',
            'subtitle' => 'Kelola bimbingan konseling dengan efisien',
            'description' => 'Pantau perkembangan siswa dan berikan bimbingan yang tepat.',
            'tips' => [
                '📊 Dashboard memberikan overview lengkap',
                '🔔 Notifikasi real-time untuk kasus urgent',
                '📈 Analisis data untuk evaluasi program'
            ],
            'color' => 'text-red-700 dark:text-red-300',
            'bg' => 'bg-red-100 dark:bg-red-800/20',
            'border' => 'border-red-300 dark:border-red-700'
        ],
        'admin' => [
            'title' => 'Selamat datang, Administrator! ⚙️',
            'subtitle' => 'Kelola sistem bimbingan konseling',
            'description' => 'Monitor seluruh aktivitas dan konfigurasi sistem.',
            'tips' => [
                '🔧 Akses penuh ke semua fitur sistem',
                '📊 Laporan komprehensif dan analisis',
                '👥 Kelola user dan permission'
            ],
            'color' => 'text-red-800 dark:text-red-200',
            'bg' => 'bg-red-200 dark:bg-red-700/20',
            'border' => 'border-red-400 dark:border-red-600'
        ],
        'orangtua' => [
            'title' => 'Selamat datang, Orang Tua! 👨‍👩‍👧‍👦',
            'subtitle' => 'Pantau perkembangan anak Anda',
            'description' => 'Dapatkan informasi terkini tentang bimbingan konseling anak Anda.',
            'tips' => [
                '📱 Notifikasi otomatis untuk kasus penting',
                '📊 Laporan perkembangan anak',
                '💬 Komunikasi langsung dengan Guru BK'
            ],
            'color' => 'text-red-500 dark:text-red-400',
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800'
        ]
    ];

    $message = $welcomeMessages[$userRole] ?? $welcomeMessages['admin'];
@endphp

<div class="welcome-banner {{ $message['bg'] }} {{ $message['border'] }} border rounded-xl p-6 mb-8">
    <div class="flex items-start space-x-4">
        <!-- Welcome Icon -->
        <div class="flex-shrink-0">
            <div class="w-12 h-12 {{ $message['color'] }} bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg">
                @if($userRole === 'siswa')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                @elseif($userRole === 'gurubk')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                @elseif($userRole === 'admin')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                @elseif($userRole === 'orangtua')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <h2 class="text-xl font-bold {{ $message['color'] }} mb-1">
                {{ $message['title'] }}
            </h2>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                {{ $message['subtitle'] }}
            </p>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                {{ $message['description'] }}
            </p>

            <!-- Tips Section -->
            <div class="space-y-1">
                @foreach($message['tips'] as $tip)
                    <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                        <span class="mr-2">{{ $tip }}</span>
                    </p>
                @endforeach
            </div>
        </div>

        <!-- Close Button -->
        <button onclick="this.parentElement.parentElement.style.display='none'" 
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
