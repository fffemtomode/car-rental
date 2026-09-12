<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-4 text-gray-900">Угода #{{ $deal->id }}</h1>

        <p class="text-gray-900">Автомобіль: {{ $deal->car->brand }} {{ $deal->car->model }}</p>
        <p class="text-gray-900">Тип: {{ $deal->type_label }}</p>
        <p class="text-gray-900">Статус: {{ $deal->status_label }}</p>
        @if ($deal->start_date)
            <p class="text-gray-900">Період: {{ $deal->start_date }} — {{ $deal->end_date }}</p>
        @endif
        @if ($deal->total_price)
            <p class="text-gray-900">Вартість: {{ $deal->total_price }} грн</p>
        @endif

        @if ($deal->type === 'leasing' && $deal->leasingSchedules->count())
            <h2 class="text-xl font-semibold mt-6 mb-2 text-gray-900">Графік платежів</h2>
            <table class="w-full border text-gray-900">
                <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Дата</th>
                    <th class="text-left p-2">Сума</th>
                    <th class="text-left p-2">Статус</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deal->leasingSchedules as $schedule)
                    <tr class="border-b">
                        <td class="p-2">{{ $schedule->payment_date }}</td>
                        <td class="p-2">{{ $schedule->amount }} грн</td>
                        <td class="p-2">{{ $schedule->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @if ($deal->status === 'pending' && in_array(auth()->user()->role, ['manager', 'admin']))
            <div class="flex gap-3 mt-4">
                <form method="POST" action="{{ route('admin.deals.confirm', $deal) }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Підтвердити угоду</button>
                </form>
                <form method="POST" action="{{ route('admin.deals.reject', $deal) }}" onsubmit="return confirm('Відхилити заявку?')">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Відхилити</button>
                </form>
            </div>
        @elseif ($deal->status === 'pending')
            <p class="mt-4 text-gray-600">Очікує підтвердження менеджером.</p>
        @endif

        @if (in_array($deal->status, ['pending', 'confirmed']) && $deal->user_id === auth()->id())
            <form method="POST" action="{{ route('deals.cancel', $deal) }}" class="mt-4" onsubmit="return confirm('Скасувати угоду?')">
                @csrf
                <button type="submit" class="text-red-600 underline text-sm">Скасувати угоду</button>
            </form>
        @endif

        @if ($deal->contract)
            <p class="mt-4">
                <a href="{{ Storage::url($deal->contract->file_path) }}" target="_blank" class="text-blue-600 underline">
                    Завантажити договір (PDF)
                </a>
            </p>
        @endif
    </div>
</x-app-layout>
