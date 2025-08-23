@props([
    'action' => '',
    'method' => 'POST',
    'userRole' => 'admin',
    'title' => '',
    'description' => ''
])

@php
    $colorSchemes = [
        'siswa' => [
            'primary' => 'border-blue-200 focus:border-blue-500 focus:ring-blue-500',
            'label' => 'text-blue-700 dark:text-blue-300',
            'bg' => 'bg-blue-50 dark:bg-blue-900/10',
            'button' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
        ],
        'gurubk' => [
            'primary' => 'border-green-200 focus:border-green-500 focus:ring-green-500',
            'label' => 'text-green-700 dark:text-green-300',
            'bg' => 'bg-green-50 dark:bg-green-900/10',
            'button' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
        ],
        'admin' => [
            'primary' => 'border-purple-200 focus:border-purple-500 focus:ring-purple-500',
            'label' => 'text-purple-700 dark:text-purple-300',
            'bg' => 'bg-purple-50 dark:bg-purple-900/10',
            'button' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500'
        ],
        'orangtua' => [
            'primary' => 'border-orange-200 focus:border-orange-500 focus:ring-orange-500',
            'label' => 'text-orange-700 dark:text-orange-300',
            'bg' => 'bg-orange-50 dark:bg-orange-900/10',
            'button' => 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500'
        ]
    ];
    
    $scheme = $colorSchemes[$userRole] ?? $colorSchemes['admin'];
@endphp

<div class="friendly-form {{ $scheme['bg'] }} rounded-xl p-6 border border-gray-200 dark:border-gray-700">
    @if($title)
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold {{ $scheme['label'] }} mb-2">{{ $title }}</h2>
        @if($description)
        <p class="text-gray-600 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>
    @endif

    <form action="{{ $action }}" method="{{ $method }}" class="space-y-6">
        @if($method !== 'GET')
            @csrf
            @if($method === 'PUT' || $method === 'PATCH')
                @method($method)
            @endif
        @endif

        {{ $slot }}
    </form>
</div>

<style>
.friendly-form input[type="text"],
.friendly-form input[type="email"],
.friendly-form input[type="password"],
.friendly-form input[type="number"],
.friendly-form input[type="date"],
.friendly-form input[type="datetime-local"],
.friendly-form textarea,
.friendly-form select {
    @apply w-full px-4 py-3 border-2 rounded-lg transition-all duration-200 ease-in-out;
    @apply bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100;
    @apply placeholder-gray-500 dark:placeholder-gray-400;
    @apply focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.friendly-form input:focus,
.friendly-form textarea:focus,
.friendly-form select:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.friendly-form label {
    @apply block text-sm font-semibold mb-2 transition-colors duration-200;
}

.friendly-form .form-group {
    @apply space-y-2;
}

.friendly-form .form-group label {
    @apply flex items-center space-x-2;
}

.friendly-form .form-group label::before {
    content: '';
    @apply w-1 h-4 rounded-full;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.friendly-form .help-text {
    @apply text-xs text-gray-500 dark:text-gray-400 mt-1;
}

.friendly-form .error-text {
    @apply text-xs text-red-600 dark:text-red-400 mt-1;
}

.friendly-form .success-text {
    @apply text-xs text-green-600 dark:text-green-400 mt-1;
}

.friendly-form button[type="submit"] {
    @apply w-full py-3 px-6 text-white font-semibold rounded-lg transition-all duration-200;
    @apply transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2;
    @apply shadow-lg hover:shadow-xl;
}

.friendly-form .form-actions {
    @apply flex space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700;
}

.friendly-form .form-actions button,
.friendly-form .form-actions a {
    @apply flex-1 py-3 px-6 font-semibold rounded-lg transition-all duration-200;
    @apply transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.friendly-form .form-actions .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500;
}

.friendly-form .form-actions .btn-secondary {
    @apply bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500;
}

.friendly-form .form-actions .btn-success {
    @apply bg-green-600 hover:bg-green-700 text-white focus:ring-green-500;
}

.friendly-form .form-actions .btn-danger {
    @apply bg-red-600 hover:bg-red-700 text-white focus:ring-red-500;
}

/* Animation for form elements */
.friendly-form .form-group {
    animation: slideInUp 0.3s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation for multiple form groups */
.friendly-form .form-group:nth-child(1) { animation-delay: 0.1s; }
.friendly-form .form-group:nth-child(2) { animation-delay: 0.2s; }
.friendly-form .form-group:nth-child(3) { animation-delay: 0.3s; }
.friendly-form .form-group:nth-child(4) { animation-delay: 0.4s; }
.friendly-form .form-group:nth-child(5) { animation-delay: 0.5s; }
</style>
