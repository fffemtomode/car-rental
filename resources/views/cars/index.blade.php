<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Каталог автомобілів</h1>

        <form method="GET" class="mb-6 flex gap-3">
            <input type="text" name="brand" value="{{ request('brand') }}" placeholder="Марка" class="border rounded px-3 py-2">
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Ціна від" class="border rounded px-3 py-2">
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Ціна до" class="border rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Фільтр</button>
        </form>

        <div class="flex flex-col gap-4">
            @forelse ($cars as $car)
                <a href="{{ route('cars.show', $car) }}" class="border rounded-lg overflow-hidden hover:shadow-lg flex flex-col sm:flex-row">
                    <div class="sm:w-64 shrink-0 aspect-video sm:aspect-auto sm:h-40">
                        @if ($car->photo)
                            <img src="{{ Storage::url($car->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm">Немає фото</div>
                        @endif
                    </div>
                    <div class="p-4 flex-1">
                        <h2 class="font-semibold text-lg text-gray-900">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h2>
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
                <p>Автомобілів поки немає.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $cars->links() }}
        </div>
    </div>
</x-app-layout>
