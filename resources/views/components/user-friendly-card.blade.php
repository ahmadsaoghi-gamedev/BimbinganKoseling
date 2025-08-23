@props([
    'title' => '',
    'description' => '',
    'icon' => '',
    'color' => 'blue',
    'href' => '#',
    'badge' => null,
    'userRole' => null
])

@php
    // Professional and clean color schemes with excellent readability
    $colorSchemes = [
        'Data Siswa' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-blue-500',
            'icon' => 'text-blue-600',
            'iconBg' => 'bg-blue-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-blue-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'
        ],
        'Data Guru BK' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-indigo-500',
            'icon' => 'text-indigo-600',
            'iconBg' => 'bg-indigo-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-indigo-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>'
        ],
        'Data Pelanggaran' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-green-500',
            'icon' => 'text-green-600',
            'iconBg' => 'bg-green-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-green-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>'
        ],
        'Rekap Bimbingan' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-red-500',
            'icon' => 'text-red-600',
            'iconBg' => 'bg-red-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-red-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        ],
        'Sistem Pemanggilan' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-orange-500',
            'icon' => 'text-orange-600',
            'iconBg' => 'bg-orange-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-orange-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>'
        ],
        'Daftar Curhat' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-purple-500',
            'icon' => 'text-purple-600',
            'iconBg' => 'bg-purple-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-purple-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>'
        ],
        'Bimbingan Lanjutan' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-cyan-500',
            'icon' => 'text-cyan-600',
            'iconBg' => 'bg-cyan-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-cyan-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        ],
        'Daftar Cek Masalah' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-teal-500',
            'icon' => 'text-teal-600',
            'iconBg' => 'bg-teal-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-teal-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-12h.008v.008H15V6zm0 3h.008v.008H15V9zm0 3h.008v.008H15V12zm0 3h.008v.008H15V15zm0 3h.008v.008H15V18zM6.75 6h.008v.008H6.75V6zm0 3h.008v.008H6.75V9zm0 3h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>'
        ],
        'Solusi Kasus' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-gray-500',
            'icon' => 'text-gray-600',
            'iconBg' => 'bg-gray-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-gray-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75-7.478a12.121 12.121 0 00-3.75-.189m0 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>'
        ],
        'Curhat Rahasia' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-pink-500',
            'icon' => 'text-pink-600',
            'iconBg' => 'bg-pink-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-pink-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>'
        ],
        'Konsultasi' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-blue-500',
            'icon' => 'text-blue-600',
            'iconBg' => 'bg-blue-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-blue-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>'
        ],
        'Pengaduan' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-red-500',
            'icon' => 'text-red-600',
            'iconBg' => 'bg-red-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-red-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>'
        ],
        'Cek Masalah' => [
            'bg' => 'bg-white',
            'border' => 'border-l-4 border-teal-500',
            'icon' => 'text-teal-600',
            'iconBg' => 'bg-teal-50',
            'title' => 'text-gray-800',
            'description' => 'text-gray-600',
            'hover' => 'hover:bg-teal-50',
            'shadow' => 'shadow-sm hover:shadow-md',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>'
        ]
    ];

    $scheme = $colorSchemes[$title] ?? $colorSchemes['Data Siswa'];
@endphp

<div class="group">
    <!-- Professional and clean card design -->
    <a href="{{ $href }}" class="block w-full h-56 {{ $scheme['bg'] }} {{ $scheme['border'] }} rounded-xl {{ $scheme['shadow'] }} {{ $scheme['hover'] }} transform hover:-translate-y-1 transition-all duration-300 ease-out overflow-hidden border border-gray-200">
        
        <div class="flex flex-col justify-center items-center h-full p-6 text-center">
            <!-- Clean and visible icon -->
            <div class="mb-4 transform group-hover:scale-110 transition-transform duration-300">
                <div class="w-12 h-12 {{ $scheme['iconBg'] }} rounded-lg flex items-center justify-center">
                    @if($icon)
                        {!! $icon !!}
                    @else
                        <svg class="w-6 h-6 {{ $scheme['icon'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            {!! $scheme['iconPath'] !!}
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Clear and readable typography -->
            <h3 class="text-lg font-semibold {{ $scheme['title'] }} text-center mb-2 leading-tight">
                {{ $title }}
            </h3>

            <!-- Easy to read description -->
            <p class="text-sm {{ $scheme['description'] }} text-center leading-relaxed max-w-xs">
                {{ $description }}
            </p>

            <!-- Badge if provided -->
            @if($badge)
                <div class="mt-3">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $scheme['iconBg'] }} {{ $scheme['icon'] }}">
                        {{ $badge }}
                    </span>
                </div>
            @endif
        </div>
    </a>
</div>