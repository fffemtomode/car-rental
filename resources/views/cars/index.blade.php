<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Каталог автомобілів</h1>

        <div class="flex flex-col sm:flex-row gap-6">
            <aside class="sm:w-64 shrink-0">
                <form method="GET" class="border rounded-lg p-4 space-y-3 bg-white">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Марка</label>
                        <input type="text" name="brand" value="{{ request('brand') }}" class="border rounded px-3 py-2 w-full bg-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Ціна від</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="border rounded px-3 py-2 w-full bg-white bg-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Ціна до</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="border rounded px-3 py-2 w-full bg-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Статус</label>
                        <select name="status" class="border rounded px-3 py-2 w-full bg-white">
                            <option value="">Усі</option>
                            <option value="available" @selected(request('status') === 'available')>Доступний</option>
                            <option value="rented" @selected(request('status') === 'rented')>В оренді</option>
                            <option value="sold" @selected(request('status') === 'sold')>Проданий</option>
                        </select>
                    </div>
                    <hr>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Вільне з</label>
                        <input type="date" name="available_from" value="{{ request('available_from') }}" class="border rounded px-3 py-2 w-full bg-white">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Вільне до</label>
                        <input type="date" name="available_to" value="{{ request('available_to') }}" class="border rounded px-3 py-2 w-full bg-white">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Застосувати</button>
                    @if (request()->hasAny(['brand', 'min_price', 'max_price', 'status', 'available_from', 'available_to']))
                        <a href="{{ route('cars.index') }}" class="block text-center text-gray-600 text-sm">Скинути фільтр</a>
                    @endif
                </form>
            </aside>

            <div class="flex-1 flex flex-col gap-4">
                @forelse ($cars as $car)
                    <a href="{{ route('cars.show', $car) }}" class="border rounded-lg overflow-hidden hover:shadow-lg flex flex-col sm:flex-row">
                        <div class="sm:w-64 shrink-0 aspect-video sm:aspect-auto sm:h-40 border-r border-gray-100">
                            @if ($car->photos->count())
                                <div x-data="{ active: 0, total: {{ $car->photos->count() }} }" class="relative w-full h-full">
                                    @foreach ($car->photos as $i => $photo)
                                        <img x-show="active === {{ $i }}" src="{{ Storage::url($photo->path) }}" class="w-full h-full object-cover">
                                    @endforeach

                                    @if ($car->photos->count() > 1)
                                        <button type="button" @click.stop.prevent="active = (active - 1 + total) % total" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">‹</button>
                                        <button type="button" @click.stop.prevent="active = (active + 1) % total" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">›</button>

                                        <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                                            @foreach ($car->photos as $i => $photo)
                                                <button type="button" @click.stop.prevent="active = {{ $i }}" :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'" class="w-2 h-2 rounded-full"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @elseif ($car->photo)
                                <img src="{{ Storage::url($car->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                                        <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex-1">
                            <h2 class="font-semibold text-lg text-gray-900">
                                {{ $car->brand }} {{ $car->model }} ({{ $car->year }})
                                @if ($car->status !== 'available')
                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded ml-1">{{ $car->status_label }}</span>
                                @endif
                            </h2>
                            @if ($car->engine)
                                <p class="text-gray-600 text-sm">Двигун: {{ $car->engine }}</p>
                            @endif
                            @if ($car->mileage)
                                <p class="text-gray-600 text-sm">Пробіг: {{ number_format($car->mileage, 0, '', ' ') }} км</p>
                            @endif
                            <p class="text-gray-900 mt-2">Оренда: <strong>{{ $car->price_per_day }} грн/добу</strong></p>
                            @if ($car->buyout_price)
                                <p class="text-gray-900">Викуп: <strong>{{ $car->buyout_price }} грн</strong></p>
                            @endif
                        </div>
                    </a>
                @empty
                    <p>Автомобілів не знайдено.</p>
                @endforelse

                <div class="mt-2">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
