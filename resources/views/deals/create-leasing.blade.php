<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Лізинг: {{ $car->brand }} {{ $car->model }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('deals.store-leasing', $car) }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-gray-900">Сума лізингу (грн)</label>
                <input type="number" name="amount" value="{{ $car->buyout_price }}" class="border rounded px-3 py-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-gray-900">Строк (місяців)</label>
                <input type="number" name="leasing_months" value="12" min="1" max="60" class="border rounded px-3 py-2 w-full" required>
            </div>
            <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Підтвердити заявку на лізинг</button>
        </form>
    </div>
</x-app-layout>
