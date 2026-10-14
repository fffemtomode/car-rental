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
            <h3 class="font-bold text-xl mb-3">Оренда</h3>
            <p class="text-gray-600">Обери авто та період — оплата тільки за дні користування.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="font-bold text-xl mb-3">Викуп</h3>
            <p class="text-gray-600">Переходь від оренди до повного володіння автомобілем.</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
            <h3 class="font-bold text-xl mb-3">Лізинг</h3>
            <p class="text-gray-600">Користуйся авто довгостроково, розділивши вартість на щомісячні платежі.</p>
        </div>
    </div>
</section>

@php
    $totalCars = \App\Models\Car::count();
    $availableCars = \App\Models\Car::where('status', 'available')->count();
    $rentedCars = \App\Models\Car::where('status', 'rented')->count();
    $soldCars = \App\Models\Car::where('status', 'sold')->count();
    $stats = [
        ['label' => 'Доступні', 'value' => $availableCars, 'color' => 'bg-green-500'],
        ['label' => 'В оренді/лізингу', 'value' => $rentedCars, 'color' => 'bg-blue-500'],
        ['label' => 'Продані', 'value' => $soldCars, 'color' => 'bg-gray-400'],
    ];
@endphp
<section class="max-w-4xl mx-auto px-4 pb-24">
    <h2 class="text-2xl font-bold text-center mb-8">Наш автопарк зараз</h2>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 space-y-4">
        @foreach ($stats as $stat)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">{{ $stat['label'] }}</span>
                    <span class="text-gray-900 font-medium">{{ $stat['value'] }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="{{ $stat['color'] }} h-3 rounded-full" style="width: {{ $totalCars ? round($stat['value'] / $totalCars * 100) : 0 }}%"></div>
                </div>
            </div>
        @endforeach
        <p class="text-center text-gray-500 text-sm pt-2">Усього автомобілів у системі: {{ $totalCars }}</p>
    </div>
</section>

@include('partials.footer')

</body>
</html>
