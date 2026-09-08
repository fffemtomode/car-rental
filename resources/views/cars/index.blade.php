<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Каталог автомобілів</h1>

        <form method="GET" class="mb-6 flex gap-3">
            <input type="text" name="brand" value="{{ request('brand') }}" placeholder="Марка" class="border rounded px-3 py-2">
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Ціна від" class="border rounded px-3 py-2">
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Ціна до" class="border rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Фільтр</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($cars as $car)
                <a href="{{ route('cars.show', $car) }}" class="border rounded-lg p-4 hover:shadow-lg">
                    <h2 class="font-semibold text-lg">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h2>
                    <p class="text-gray-600">Оренда: {{ $car->price_per_day }} грн/добу</p>
                    @if ($car->buyout_price)
                        <p class="text-gray-600">Викуп: {{ $car->buyout_price }} грн</p>
                    @endif
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
