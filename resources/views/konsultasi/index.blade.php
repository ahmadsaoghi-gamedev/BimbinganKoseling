<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Red Header Section -->
        <div class="bg-red-800 text-white py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold">RIWAYAT CURHAT RAHASIA</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($consultations->isEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <i class="fas fa-comments text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg mb-4">Anda belum pernah mengirim curhat.</p>
                        <a href="{{ route('konsultasi.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Kirim Curhat Pertama
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($consultations as $consultation)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Header Card -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Curhat Anda</h3>
                                            <p class="text-sm text-gray-500">{{ $consultation->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($consultation->reply_guru)
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Ada Balasan
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Menunggu Balasan
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Your Message -->
                                <div class="mb-6">
                                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-blue-800">Pesan Anda</span>
                                                </div>
                                                <p class="text-gray-800 leading-relaxed">{{ $consultation->isi_curhat }}</p>
                                                @if($consultation->attachment)
                                                    <div class="mt-3">
                                                        <a href="{{ asset('storage/' . $consultation->attachment) }}" 
                                                           target="_blank" 
                                                           class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                                            <i class="fas fa-paperclip mr-2"></i>
                                                            Lihat Lampiran
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Teacher Reply -->
                                @if($consultation->reply_guru)
                                    <div class="mb-6">
                                        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-sm font-medium text-green-800">Balasan Guru BK</span>
                                                        <span class="text-xs text-green-600">{{ $consultation->reply_date->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <p class="text-gray-800 leading-relaxed">{{ $consultation->reply_guru }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-6 border-t border-gray-200">
                                        <i class="fas fa-hourglass-half text-gray-400 text-3xl mb-2"></i>
                                        <p class="text-gray-500">Guru BK sedang memproses curhat Anda. Mohon tunggu balasan.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Add New Consultation Button -->
                    <div class="mt-8 text-center">
                        <a href="{{ route('konsultasi.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Kirim Curhat Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SweetAlert for notifications -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });

        @if(session('alert-type'))
            var type = "{{ Session::get('alert-type') }}";
            switch (type) {
                case 'success':
                    Toast.fire({
                        icon: 'success',
                        title: "{{ Session::get('message') }}"
                    });
                    break;
                case 'error':
                    Toast.fire({
                        icon: 'error',
                        title: "{{ Session::get('message') }}"
                    });
                    break;
            }
        @endif
    </script>
</body>
</html>
