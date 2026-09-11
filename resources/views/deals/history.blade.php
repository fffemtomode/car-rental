<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Мої угоди</h1>

        @if ($deals->isEmpty())
            <p class="text-gray-900">У тебе поки немає угод.</p>
        @else
            <table class="w-full border text-gray-900">
                <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left p-2">Авто</th>
                    <th class="text-left p-2">Тип</th>
                    <th class="text-left p-2">Статус</th>
                    <th class="text-left p-2">Вартість</th>
                    <th class="text-left p-2"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deals as $deal)
                    <tr class="border-b">
                        <td class="p-2">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                        <td class="p-2">{{ $deal->type_label }}</td>
                        <td class="p-2">{{ $deal->status_label }}</td>
                        <td class="p-2">{{ $deal->total_price }} грн</td>
                        <td class="p-2">
                            <a href="{{ route('deals.show', $deal) }}" class="text-blue-600">Деталі</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
