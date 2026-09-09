<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h1>

        @if ($car->photo)
            <img src="{{ Storage::url($car->photo) }}" class="w-full max-h-80 object-cover rounded mb-4">
        @endif

        @if ($car->engine)
            <p class="mb-2 text-gray-900">Двигун: {{ $car->engine }}</p>
        @endif
        @if ($car->mileage)
            <p class="mb-2 text-gray-900">Пробіг: {{ number_format($car->mileage, 0, '', ' ') }} км</p>
        @endif

        <p class="mb-2">Ціна оренди: <strong>{{ $car->price_per_day }} грн/добу</strong></p>
        @if ($car->buyout_price)
            <p class="mb-2">Ціна викупу: <strong>{{ $car->buyout_price }} грн</strong></p>
        @endif
        <p class="mb-4">Статус: {{ $car->status }}</p>

        <div class="flex gap-3">
            <a href="{{ route('deals.create-rental', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Орендувати</a>
            <a href="{{ route('deals.create-buyout', $car) }}" class="bg-green-600 text-white px-4 py-2 rounded">Викупити</a>
            <a href="{{ route('deals.create-leasing', $car) }}" class="bg-purple-600 text-white px-4 py-2 rounded">Лізинг</a>
        </div>
    </div>
</x-app-layout>
