<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Вітаємо, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4">

            @if (in_array(auth()->user()->role, ['manager', 'admin']))
                @php
                    $pendingCount = \App\Models\Deal::where('status', 'pending')->count();
                    $carsCount = \App\Models\Car::count();
                    $availableCount = \App\Models\Car::where('status', 'available')->count();
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('admin.deals.index') }}" class="bg-white border rounded-lg p-6 hover:shadow">
                        <div class="text-3xl font-bold text-blue-600">{{ $pendingCount }}</div>
                        <div class="text-gray-600 mt-1">Заявок очікують підтвердження</div>
                    </a>
                    <a href="{{ route('admin.cars.index') }}" class="bg-white border rounded-lg p-6 hover:shadow">
                        <div class="text-3xl font-bold text-gray-900">{{ $carsCount }}</div>
                        <div class="text-gray-600 mt-1">Автомобілів у системі</div>
                    </a>
                    <div class="bg-white border rounded-lg p-6">
                        <div class="text-3xl font-bold text-green-600">{{ $availableCount }}</div>
                        <div class="text-gray-600 mt-1">Доступні для оренди</div>
                    </div>
                </div>
            @else
                @php
                    $activeDeals = auth()->user()->deals()->whereIn('status', ['pending', 'confirmed'])->count();
                @endphp
                <div class="bg-white border rounded-lg p-6 mb-6">
                    <p class="text-gray-900">У тебе <strong>{{ $activeDeals }}</strong> активних угод.</p>
                    <a href="{{ route('deals.history') }}" class="text-blue-600 hover:underline">Переглянути мої угоди →</a>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('cars.index') }}" class="bg-blue-600 text-white rounded-lg p-6 hover:bg-blue-700">
                    <div class="font-semibold text-lg">Каталог автомобілів</div>
                    <div class="text-blue-100 text-sm mt-1">Обрати авто для оренди, викупу або лізингу</div>
                </a>
                <a href="{{ route('deals.history') }}" class="bg-white border rounded-lg p-6 hover:shadow">
                    <div class="font-semibold text-lg text-gray-900">Мої угоди</div>
                    <div class="text-gray-600 text-sm mt-1">Історія та статуси твоїх заявок</div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
