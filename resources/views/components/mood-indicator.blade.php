@props([
    'mood' => 'neutral',
    'size' => 'md',
    'interactive' => false,
    'onMoodChange' => null
])

@php
    $moods = [
        'excellent' => [
            'icon' => '😊',
            'label' => 'Sangat Baik',
            'color' => 'text-green-600 dark:text-green-400',
            'bg' => 'bg-green-100 dark:bg-green-900/30',
            'border' => 'border-green-300 dark:border-green-700'
        ],
        'good' => [
            'icon' => '🙂',
            'label' => 'Baik',
            'color' => 'text-blue-600 dark:text-blue-400',
            'bg' => 'bg-blue-100 dark:bg-blue-900/30',
            'border' => 'border-blue-300 dark:border-blue-700'
        ],
        'neutral' => [
            'icon' => '😐',
            'label' => 'Biasa',
            'color' => 'text-yellow-600 dark:text-yellow-400',
            'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
            'border' => 'border-yellow-300 dark:border-yellow-700'
        ],
        'bad' => [
            'icon' => '😔',
            'label' => 'Kurang Baik',
            'color' => 'text-orange-600 dark:text-orange-400',
            'bg' => 'bg-orange-100 dark:bg-orange-900/30',
            'border' => 'border-orange-300 dark:border-orange-700'
        ],
        'terrible' => [
            'icon' => '😢',
            'label' => 'Sangat Buruk',
            'color' => 'text-red-600 dark:text-red-400',
            'bg' => 'bg-red-100 dark:bg-red-900/30',
            'border' => 'border-red-300 dark:border-red-700'
        ]
    ];
    
    $sizeClasses = [
        'sm' => 'w-8 h-8 text-lg',
        'md' => 'w-12 h-12 text-2xl',
        'lg' => 'w-16 h-16 text-3xl'
    ];
    
    $currentMood = $moods[$mood] ?? $moods['neutral'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="mood-indicator">
    @if($interactive)
        <!-- Interactive Mood Selector -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Bagaimana perasaanmu hari ini?
            </label>
            <div class="flex space-x-3">
                @foreach($moods as $moodKey => $moodData)
                    <button type="button"
                            onclick="selectMood('{{ $moodKey }}')"
                            class="mood-option {{ $moodKey === $mood ? 'selected' : '' }} {{ $moodData['bg'] }} {{ $moodData['border'] }} border-2 rounded-lg p-3 transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            data-mood="{{ $moodKey }}">
                        <div class="{{ $sizeClass }} flex items-center justify-center {{ $moodData['color'] }}">
                            {{ $moodData['icon'] }}
                        </div>
                        <div class="text-xs font-medium {{ $moodData['color'] }} mt-1 text-center">
                            {{ $moodData['label'] }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        
        <script>
            function selectMood(mood) {
                // Remove selected class from all options
                document.querySelectorAll('.mood-option').forEach(option => {
                    option.classList.remove('selected', 'ring-2', 'ring-blue-500');
                });
                
                // Add selected class to clicked option
                const selectedOption = document.querySelector(`[data-mood="${mood}"]`);
                selectedOption.classList.add('selected', 'ring-2', 'ring-blue-500');
                
                // Trigger mood change event if callback provided
                @if($onMoodChange)
                    {{ $onMoodChange }}(mood);
                @endif
                
                // Add visual feedback
                selectedOption.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    selectedOption.style.transform = 'scale(1.05)';
                }, 200);
            }
        </script>
        
        <style>
            .mood-option.selected {
                transform: scale(1.05);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
        </style>
    @else
        <!-- Static Mood Display -->
        <div class="flex items-center space-x-3">
            <div class="{{ $sizeClass }} {{ $currentMood['bg'] }} {{ $currentMood['border'] }} border-2 rounded-lg flex items-center justify-center {{ $currentMood['color'] }}">
                {{ $currentMood['icon'] }}
            </div>
            <div>
                <div class="text-sm font-medium {{ $currentMood['color'] }}">
                    {{ $currentMood['label'] }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Mood saat ini
                </div>
            </div>
        </div>
    @endif
</div>
