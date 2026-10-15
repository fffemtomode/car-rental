<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Викуп автомобіля</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                @php $thumb = $car->photos->first()?->path ?? $car->photo; @endphp
                @if ($thumb)
                    <img src="{{ Storage::url($thumb) }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12">
                            <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </div>
                @endif
                <div class="p-4 text-sm">
                    <h2 class="font-semibold text-gray-900 text-lg mb-2">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h2>
                    @if ($car->engine)<p class="text-gray-500">Двигун: {{ $car->engine }}</p>@endif
                    @if ($car->mileage)<p class="text-gray-500">Пробіг: {{ number_format($car->mileage, 0, '', ' ') }} км</p>@endif
                    @if ($car->color)<p class="text-gray-500">Колір: {{ $car->color }}</p>@endif
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <p class="text-gray-500 text-sm mb-1">Вартість викупу</p>
                <p class="text-3xl font-extrabold text-gray-900 mb-6">{{ number_format($car->buyout_price, 0, '', ' ') }} грн</p>

                <form method="POST" action="{{ route('deals.store-buyout', $car) }}">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 w-full">Підтвердити заявку на викуп</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
