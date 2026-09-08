<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h1>

        <p class="mb-2">Ціна оренди: <strong>{{ $car->price_per_day }} грн/добу</strong></p>
        @if ($car->buyout_price)
            <p class="mb-2">Ціна викупу: <strong>{{ $car->buyout_price }} грн</strong></p>
        @endif
        <p class="mb-4">Статус: {{ $car->status }}</p>

        <div class="flex gap-3">
            <a href="{{ route('deals.create-rental', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Орендувати</a>
            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded">Викупити</a>
            <a href="#" class="bg-purple-600 text-white px-4 py-2 rounded">Лізинг</a>
        </div>
    </div>
</x-app-layout>
