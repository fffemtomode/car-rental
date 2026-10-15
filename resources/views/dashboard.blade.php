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
                    $today = now()->toDateString();
                    $pendingCount = \App\Models\Deal::where('status', 'pending')->count();
                    $carsCount = \App\Models\Car::count();
                    $availableCount = \App\Models\Car::where('status', 'available')->count();
                    $activeDeals = \App\Models\Deal::with(['car', 'user'])
                        ->where('status', 'confirmed')
                        ->where(function ($q) use ($today) {
                            $q->where(function ($q2) use ($today) {
                                $q2->where('type', 'rental')
                                    ->where('start_date', '<=', $today)
                                    ->where('end_date', '>=', $today);
                            })->orWhere('type', 'leasing');
                        })->get();
                    $needsMaintenanceCount = \App\Models\Car::all()->filter(fn ($c) => $c->needsMaintenance())->count();
                    $topCars = \App\Models\Car::withCount(['deals as rentals_count' => function ($q) {
                        $q->where('type', 'rental');
                    }])->orderByDesc('rentals_count')->take(5)->get();
                    $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
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
                    <a href="{{ route('admin.cars.index', ['status' => 'available']) }}" class="bg-white border rounded-lg p-6 hover:shadow">
                        <div class="text-3xl font-bold text-green-600">{{ $availableCount }}</div>
                        <div class="text-gray-600 mt-1">Доступні для оренди</div>
                    </a>
                    <a href="{{ route('admin.cars.index', ['needs_maintenance' => 1]) }}" class="bg-white border rounded-lg p-6 hover:shadow">
                        <div class="text-3xl font-bold text-red-600">{{ $needsMaintenanceCount }}</div>
                        <div class="text-gray-600 mt-1">Потребують ТО</div>
                    </a>
                </div>

                @if ($pendingReviews > 0)
                    <a href="{{ route('admin.reviews.index') }}" class="block bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 hover:bg-amber-100">
                        <span class="font-medium text-amber-800">{{ $pendingReviews }}</span>
                        <span class="text-amber-700"> відгук(ів) очікують модерації →</span>
                    </a>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-white border rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Авто зараз в оренді/лізингу</h3>
                        @if ($activeDeals->isEmpty())
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
                                @foreach ($activeDeals as $ad)
                                    <tr class="border-b">
                                        <td class="py-1">{{ $ad->car->brand }} {{ $ad->car->model }}</td>
                                        <td class="py-1">{{ $ad->user->name }}</td>
                                        <td class="py-1">{{ $ad->end_date ?? 'лізинг' }}</td>
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
                    $activeDealsCount = auth()->user()->deals()->whereIn('status', ['pending', 'confirmed'])->count();
                    $recentDeals = auth()->user()->deals()->with('car')->latest()->take(3)->get();
                    $recommendedCars = \App\Models\Car::where('status', 'available')->inRandomOrder()->take(3)->get();
                @endphp
                <div class="bg-white border rounded-lg p-6 mb-6">
                    <p class="text-gray-900">У тебе <strong>{{ $activeDealsCount }}</strong> активних угод.</p>
                    <a href="{{ route('deals.history') }}" class="text-blue-600 hover:underline">Переглянути мої угоди →</a>
                </div>

                @if ($recentDeals->isNotEmpty())
                    <div class="bg-white border rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Останні угоди</h3>
                        <div class="space-y-2">
                            @foreach ($recentDeals as $rd)
                                <a href="{{ route('deals.show', $rd) }}" class="flex justify-between items-center text-sm border-b last:border-0 py-2 hover:bg-gray-50 px-2 -mx-2 rounded">
                                    <span class="text-gray-900">{{ $rd->car->brand }} {{ $rd->car->model }} · {{ $rd->type_label }}</span>
                                    <span class="text-gray-500">{{ $rd->status_label }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($recommendedCars->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Можливо, тобі сподобається</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach ($recommendedCars as $rc)
                                <a href="{{ route('cars.show', $rc) }}" class="bg-white border rounded-lg overflow-hidden hover:shadow">
                                    @php $thumb = $rc->photos->first()?->path ?? $rc->photo; @endphp
                                    @if ($thumb)
                                        <img src="{{ Storage::url($thumb) }}" class="w-full h-28 object-cover">
                                    @else
                                        <div class="w-full h-28 bg-gray-100 flex items-center justify-center text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                                <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-3 text-sm">
                                        <p class="font-medium text-gray-900">{{ $rc->brand }} {{ $rc->model }}</p>
                                        <p class="text-gray-500">{{ $rc->price_per_day }} грн/добу</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
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
