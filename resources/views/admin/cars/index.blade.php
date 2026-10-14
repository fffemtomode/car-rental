<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Управління автомобілями</h1>
            <a href="{{ route('admin.cars.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-blue-700">Додати авто</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4 flex flex-wrap gap-3">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 text-sm">
                <option value="">Усі статуси</option>
                <option value="available" @selected(request('status') === 'available')>Доступний</option>
                <option value="rented" @selected(request('status') === 'rented')>В оренді</option>
                <option value="sold" @selected(request('status') === 'sold')>Проданий</option>
                <option value="maintenance" @selected(request('status') === 'maintenance')>На обслуговуванні</option>
            </select>

            <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 text-sm">
                <option value="">Сортування за замовчуванням</option>
                <option value="available_first" @selected(request('sort') === 'available_first')>Спочатку доступні</option>
                <option value="maintenance_first" @selected(request('sort') === 'maintenance_first')>Спочатку потребують ТО</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Ціна: зростання</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Ціна: спадання</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Застосувати</button>
            @if (request()->hasAny(['status', 'sort', 'needs_maintenance']))
                <a href="{{ route('admin.cars.index') }}" class="text-gray-600 px-4 py-2">Скинути</a>
            @endif
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-gray-900">
                <thead>
                <tr class="bg-gray-50 border-b-2 border-gray-200">
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Фото</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Марка/Модель</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Рік</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Ціна/день</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">ТО</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cars as $car)
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @php $thumb = $car->photos->first()?->path ?? $car->photo; @endphp
                            @if ($thumb)
                                <img src="{{ Storage::url($thumb) }}" class="w-20 h-14 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-14 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $car->brand }} {{ $car->model }}</td>
                        <td class="px-4 py-3">{{ $car->year }}</td>
                        <td class="px-4 py-3">{{ $car->price_per_day }} грн</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                {{ $car->status === 'available' ? 'bg-green-100 text-green-700' :
                                   ($car->status === 'rented' ? 'bg-blue-100 text-blue-700' :
                                   ($car->status === 'sold' ? 'bg-gray-200 text-gray-600' : 'bg-orange-100 text-orange-700')) }}">
                                {{ $car->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($car->needsMaintenance())
                                <span class="text-xs font-medium bg-red-100 text-red-700 px-2 py-1 rounded-full">Потрібне ТО</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.cars.calendar', $car) }}" class="text-green-700 hover:underline">Календар</a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('admin.cars.edit', $car) }}" class="text-blue-600 hover:underline">Редагувати</a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('admin.cars.maintenance', $car) }}" class="text-purple-600 hover:underline">ТО</a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Видалити авто?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Видалити</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $cars->links() }}</div>
    </div>
</x-app-layout>
