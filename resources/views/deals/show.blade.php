<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-4 text-gray-900">Угода #{{ $deal->id }}</h1>

        <p class="text-gray-900">Автомобіль: {{ $deal->car->brand }} {{ $deal->car->model }}</p>
        <p class="text-gray-900">Тип: {{ $deal->type }}</p>
        <p class="text-gray-900">Статус: {{ $deal->status }}</p>
        @if ($deal->start_date)
            <p class="text-gray-900">Період: {{ $deal->start_date }} — {{ $deal->end_date }}</p>
        @endif
        @if ($deal->total_price)
            <p class="text-gray-900">Вартість: {{ $deal->total_price }} грн</p>
        @endif
    </div>
</x-app-layout>
