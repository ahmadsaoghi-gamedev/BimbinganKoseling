@props([
    'current' => 0,
    'total' => 100,
    'label' => 'Progress',
    'color' => 'blue',
    'showPercentage' => true,
    'size' => 'md'
])

@php
    $percentage = $total > 0 ? round(($current / $total) * 100) : 0;
    
    $colorSchemes = [
        'blue' => [
            'bg' => 'bg-blue-200 dark:bg-blue-800',
            'fill' => 'bg-blue-500',
            'text' => 'text-blue-600 dark:text-blue-400'
        ],
        'green' => [
            'bg' => 'bg-green-200 dark:bg-green-800',
            'fill' => 'bg-green-500',
            'text' => 'text-green-600 dark:text-green-400'
        ],
        'yellow' => [
            'bg' => 'bg-yellow-200 dark:bg-yellow-800',
            'fill' => 'bg-yellow-500',
            'text' => 'text-yellow-600 dark:text-yellow-400'
        ],
        'red' => [
            'bg' => 'bg-red-200 dark:bg-red-800',
            'fill' => 'bg-red-500',
            'text' => 'text-red-600 dark:text-red-400'
        ]
    ];
    
    $scheme = $colorSchemes[$color] ?? $colorSchemes['blue'];
    
    $sizeClasses = [
        'sm' => 'h-2 text-xs',
        'md' => 'h-3 text-sm',
        'lg' => 'h-4 text-base'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="friendly-progress">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
        @if($showPercentage)
            <span class="text-sm font-semibold {{ $scheme['text'] }}">
                {{ $percentage }}%
            </span>
        @endif
    </div>
    
    <div class="relative">
        <!-- Background bar -->
        <div class="w-full {{ $scheme['bg'] }} rounded-full {{ $sizeClass }}">
            <!-- Progress fill with animation -->
            <div class="{{ $scheme['fill'] }} h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden"
                 style="width: {{ $percentage }}%">
                
                <!-- Animated shimmer effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                
                <!-- Progress dots for visual appeal -->
                @if($percentage > 0)
                    <div class="absolute right-1 top-1/2 transform -translate-y-1/2">
                        <div class="w-1 h-1 bg-white rounded-full opacity-75"></div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Progress indicator -->
        @if($percentage > 0)
            <div class="absolute -top-1 transform -translate-x-1/2 transition-all duration-300"
                 style="left: {{ $percentage }}%">
                <div class="w-3 h-3 {{ $scheme['fill'] }} rounded-full shadow-lg border-2 border-white dark:border-gray-800"></div>
            </div>
        @endif
    </div>
    
    <!-- Friendly message based on progress -->
    @if($showPercentage)
        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
            @if($percentage >= 100)
                <span class="text-green-600 dark:text-green-400">🎉 Selesai! Bagus sekali!</span>
            @elseif($percentage >= 75)
                <span class="text-blue-600 dark:text-blue-400">👍 Hampir selesai! Terus semangat!</span>
            @elseif($percentage >= 50)
                <span class="text-yellow-600 dark:text-yellow-400">💪 Setengah jalan! Kamu bisa!</span>
            @elseif($percentage >= 25)
                <span class="text-orange-600 dark:text-orange-400">🚀 Mulai dengan baik! Lanjutkan!</span>
            @else
                <span class="text-gray-600 dark:text-gray-400">🌟 Ayo mulai! Setiap langkah berarti!</span>
            @endif
        </div>
    @endif
</div>
