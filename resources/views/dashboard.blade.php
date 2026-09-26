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
                    $rentedCars = \App\Models\Car::where('status', 'rented')->with(['deals' => function ($q) {
                        $q->whereIn('status', ['confirmed'])->with('user')->latest();
                    }])->get();
                    $needsMaintenanceCount = \App\Models\Car::all()->filter(fn ($c) => $c->needsMaintenance())->count();
                    $topCars = \App\Models\Car::withCount(['deals as rentals_count' => function ($q) {
                        $q->where('type', 'rental');
                    }])->orderByDesc('rentals_count')->take(5)->get();
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
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
                    <div class="bg-white border rounded-lg p-6">
                        <div class="text-3xl font-bold text-red-600">{{ $needsMaintenanceCount }}</div>
                        <div class="text-gray-600 mt-1">Потребують ТО</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white border rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Авто зараз в оренді/лізингу</h3>
                        @if ($rentedCars->isEmpty())
                            <p class="text-gray-500 text-sm">Немає авто в оренді.</p>
                        @else
                            <table class="w-full text-sm text-gray-900">
                                <thead>
                                <tr class="border-b text-left text-gray-500">
                                    <th class="py-1">Авто</th>
                                    <th class="py-1">Клієнт</th>
                                    <th class="py-1">До</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($rentedCars as $rc)
                                    @php $activeDeal = $rc->deals->first(); @endphp
                                    <tr class="border-b">
                                        <td class="py-1">{{ $rc->brand }} {{ $rc->model }}</td>
                                        <td class="py-1">{{ $activeDeal->user->name ?? '—' }}</td>
                                        <td class="py-1">{{ $activeDeal->end_date ?? '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <div class="bg-white border rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Найпопулярніші авто (за оренду)</h3>
                        <table class="w-full text-sm text-gray-900">
                            <thead>
                            <tr class="border-b text-left text-gray-500">
                                <th class="py-1">Авто</th>
                                <th class="py-1">Оренд</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($topCars as $tc)
                                <tr class="border-b">
                                    <td class="py-1">{{ $tc->brand }} {{ $tc->model }}</td>
                                    <td class="py-1">{{ $tc->rentals_count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
