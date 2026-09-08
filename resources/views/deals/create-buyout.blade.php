<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Викуп: {{ $car->brand }} {{ $car->model }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <p class="text-gray-900 mb-4">Вартість викупу: <strong>{{ $car->buyout_price }} грн</strong></p>

        <form method="POST" action="{{ route('deals.store-buyout', $car) }}">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Підтвердити заявку на викуп</button>
        </form>
    </div>
</x-app-layout>
