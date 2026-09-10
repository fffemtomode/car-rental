<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CarHub — оренда, викуп та лізинг авто</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<!-- Header -->
<header class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <span class="text-xl font-bold text-blue-600">CarHub</span>

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
<section class="max-w-6xl mx-auto px-4 py-20 text-center">
    <h1 class="text-4xl sm:text-5xl font-bold mb-4">
        Оренда, викуп та лізинг авто — в одному місці
    </h1>
    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
        Обери авто з каталогу і формат користування, який тобі зручний. Оформлення заявки й договору — онлайн, за кілька хвилин.
    </p>
    <a href="{{ route('cars.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg text-lg hover:bg-blue-700">
        Переглянути каталог
    </a>
</section>

<!-- Features -->
<section class="max-w-6xl mx-auto px-4 pb-20">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-2">Оренда</h3>
            <p class="text-gray-600 text-sm">Обери авто та період — оплата тільки за дні користування.</p>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-2">Викуп</h3>
            <p class="text-gray-600 text-sm">Переходь від оренди до повного володіння автомобілем.</p>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-2">Лізинг</h3>
            <p class="text-gray-600 text-sm">Користуйся авто довгостроково, розділивши вартість на щомісячні платежі.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-gray-100 py-6 text-center text-sm text-gray-500">
    CarHub — курсовий проєкт, {{ date('Y') }}
</footer>

</body>
</html>
