<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CarRental — оренда, викуп та лізинг авто</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🚗</text></svg>">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<header class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <span class="text-2xl font-extrabold text-blue-600">🚗 CarRental</span>

        <nav class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">Кабінет</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Увійти</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">Зареєструватись</a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>
</header>

<section class="max-w-6xl mx-auto px-4 pt-16 pb-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
    <div class="text-center lg:text-left">
        <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-6">
            Оренда, викуп та лізинг авто — в одному місці
        </h1>
        <p class="text-xl text-gray-600 mb-10 max-w-xl mx-auto lg:mx-0">
            Обери авто з каталогу і формат користування, який тобі зручний. Оформлення заявки й договору — онлайн, за кілька хвилин.
        </p>
        <a href="{{ route('cars.index') }}" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-blue-700">
            Переглянути каталог
        </a>
    </div>

    <div class="flex justify-center">
        <svg viewBox="0 0 400 220" class="w-full max-w-md">
            <ellipse cx="200" cy="195" rx="160" ry="12" fill="#e5e7eb"/>
            <path d="M40 150 Q40 110 90 105 L120 75 Q140 60 170 60 L250 60 Q280 60 300 80 L330 105 Q365 108 365 145 L365 150 Q365 165 350 165 L45 165 Q30 165 30 150 Z" fill="#2563eb"/>
            <path d="M120 105 L140 78 Q150 68 165 68 L200 68 L200 105 Z" fill="#93c5fd"/>
            <path d="M205 105 L205 68 L245 68 Q262 68 272 82 L290 105 Z" fill="#93c5fd"/>
            <rect x="40" y="150" width="325" height="8" fill="#1e3a8a"/>
            <circle cx="110" cy="165" r="28" fill="#111827"/>
            <circle cx="110" cy="165" r="12" fill="#9ca3af"/>
            <circle cx="290" cy="165" r="28" fill="#111827"/>
            <circle cx="290" cy="165" r="12" fill="#9ca3af"/>
            <rect x="330" y="118" width="18" height="10" rx="2" fill="#fde68a"/>
            <rect x="55" y="120" width="14" height="8" rx="2" fill="#fca5a5"/>
        </svg>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 pb-16">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="font-bold text-xl mb-3">1. Оренда</h3>
            <p class="text-gray-600">Обери авто та період — оплата тільки за дні користування.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="font-bold text-xl mb-3">2. Викуп</h3>
            <p class="text-gray-600">Переходь від оренди до повного володіння автомобілем.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="font-bold text-xl mb-3">3. Лізинг</h3>
            <p class="text-gray-600">Користуйся авто довгостроково, розділивши вартість на щомісячні платежі.</p>
        </div>
    </div>
</section>

{{-- Справжня кругова діаграма (donut chart) --}}
@php
    $totalCars = \App\Models\Car::count();
    $availableCars = \App\Models\Car::where('status', 'available')->count();
    $rentedCars = \App\Models\Car::where('status', 'rented')->count();
    $soldCars = \App\Models\Car::where('status', 'sold')->count();
    $maintenanceCars = \App\Models\Car::where('status', 'maintenance')->count();

    $segments = [
        ['label' => 'Доступні', 'value' => $availableCars, 'color' => '#22c55e'],
        ['label' => 'В оренді/лізингу', 'value' => $rentedCars, 'color' => '#3b82f6'],
        ['label' => 'Продані', 'value' => $soldCars, 'color' => '#9ca3af'],
        ['label' => 'На ТО', 'value' => $maintenanceCars, 'color' => '#f97316'],
    ];

    $radius = 80;
    $circumference = 2 * M_PI * $radius;
    $cumulativeOffset = 0;
@endphp
<section class="max-w-4xl mx-auto px-4 pb-24">
    <h2 class="text-2xl font-bold text-center mb-8">Наш автопарк зараз</h2>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 grid grid-cols-1 sm:grid-cols-2 gap-8 items-center">
        <div class="flex justify-center">
            <svg viewBox="0 0 200 200" class="w-56 h-56 -rotate-90">
                <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#f3f4f6" stroke-width="28"/>
                @foreach ($segments as $segment)
                    @php
                        $fraction = $totalCars > 0 ? $segment['value'] / $totalCars : 0;
                        $dash = $fraction * $circumference;
                    @endphp
                    @if ($segment['value'] > 0)
                        <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="{{ $segment['color'] }}" stroke-width="28"
                                stroke-dasharray="{{ $dash }} {{ $circumference - $dash }}"
                                stroke-dashoffset="{{ -$cumulativeOffset }}" />
                        @php $cumulativeOffset += $dash; @endphp
                    @endif
                @endforeach
            </svg>
        </div>
        <div>
            <div class="text-4xl font-extrabold text-gray-900 mb-1">{{ $totalCars }}</div>
            <div class="text-gray-500 mb-5">автомобілів у системі</div>
            <div class="space-y-2">
                @foreach ($segments as $segment)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full inline-block" style="background: {{ $segment['color'] }}"></span>
                            <span class="text-gray-700">{{ $segment['label'] }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $segment['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Популярні авто --}}
@php
    $popularCars = \App\Models\Car::withCount(['deals as rentals_count' => function ($q) {
        $q->where('type', 'rental');
    }])->where('status', 'available')->orderByDesc('rentals_count')->take(3)->get();
@endphp
@if ($popularCars->isNotEmpty())
    <section class="max-w-6xl mx-auto px-4 pb-24">
        <h2 class="text-2xl font-bold text-center mb-8">Популярні автомобілі</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($popularCars as $car)
                <a href="{{ route('cars.show', $car) }}" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    @php $thumb = $car->photos->first()?->path ?? $car->photo; @endphp
                    @if ($thumb)
                        <img src="{{ Storage::url($thumb) }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12">
                                <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="text-gray-500 text-sm">{{ $car->year }} рік</p>
                        <p class="text-blue-600 font-medium mt-2">{{ $car->price_per_day }} грн/добу</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- Відгуки --}}
@php
    $testimonials = \App\Models\Review::with(['user', 'car'])->where('status', 'approved')->latest()->take(3)->get();
@endphp
@if ($testimonials->isNotEmpty())
    <section class="max-w-6xl mx-auto px-4 pb-24">
        <h2 class="text-2xl font-bold text-center mb-8">Що кажуть клієнти</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($testimonials as $review)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="text-yellow-500 mb-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                    <p class="text-gray-700 text-sm mb-4">"{{ $review->text }}"</p>
                    <p class="text-gray-900 font-medium text-sm">{{ $review->user->name }}</p>
                    @if ($review->car)
                        <p class="text-gray-400 text-xs">{{ $review->car->brand }} {{ $review->car->model }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif

@include('partials.footer')

</body>
</html>
