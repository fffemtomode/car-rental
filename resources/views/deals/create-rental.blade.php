<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Оренда: {{ $car->brand }} {{ $car->model }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('deals.store-rental', $car) }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Дата початку</label>
                <input type="date" name="start_date" class="border rounded px-3 py-2 w-full" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Дата завершення</label>
                <input type="date" name="end_date" class="border rounded px-3 py-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Підтвердити заявку</button>
        </form>
    </div>
</x-app-layout>
