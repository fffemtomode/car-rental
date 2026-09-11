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

<!-- Header -->
<header class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <span class="text-2xl font-bold text-blue-600">CarRental</span>

        <nav class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">Кабінет</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Увійти</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded">Зареєструватись</a>
                    @endif
                @endauth
            @endif
        </nav>
    </div>
</header>

<!-- Hero -->
<section class="max-w-5xl mx-auto px-4 pt-24 pb-24 text-center">
    <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-6">
        Оренда, викуп та лізинг<br>авто — в одному місці
    </h1>
    <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
        Обери авто з каталогу і формат користування, який тобі зручний. Оформлення заявки й договору — онлайн, за кілька хвилин.
    </p>
    <a href="{{ route('cars.index') }}" class="inline-block bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-blue-700">
        Переглянути каталог
    </a>
</section>

<!-- Features -->
<section class="max-w-6xl mx-auto px-4 pb-24">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border rounded-xl p-8">
            <h3 class="font-bold text-xl mb-3">Оренда</h3>
            <p class="text-gray-600">Обери авто та період — оплата тільки за дні користування.</p>
        </div>
        <div class="bg-white border rounded-xl p-8">
            <h3 class="font-bold text-xl mb-3">Викуп</h3>
            <p class="text-gray-600">Переходь від оренди до повного володіння автомобілем.</p>
        </div>
        <div class="bg-white border rounded-xl p-8">
            <h3 class="font-bold text-xl mb-3">Лізинг</h3>
            <p class="text-gray-600">Користуйся авто довгостроково, розділивши вартість на щомісячні платежі.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-gray-100 py-6 text-center text-sm text-gray-500">
    CarRental — курсовий проєкт, {{ date('Y') }}
</footer>

</body>
</html>
