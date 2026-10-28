<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Мої угоди</h1>

        @if ($deals->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                У тебе поки немає угод.
                <div class="mt-3">
                    <a href="{{ route('cars.index') }}" class="text-blue-600 hover:underline">Переглянути каталог →</a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-gray-900">
                    <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Авто</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Тип</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Вартість</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deals as $deal)
                        <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                            <td class="px-4 py-3">{{ $deal->type_label }}</td>
                            <td class="px-4 py-3">
                                    <span class="text-xs font-medium px-2 py-1 rounded-full
                                        {{ $deal->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                           ($deal->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                                           ($deal->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600')) }}">
                                        {{ $deal->status_label }}
                                    </span>
                            </td>
                            <td class="px-4 py-3">{{ $deal->total_price }} грн</td>
                            <td class="px-4 py-3"><a href="{{ route('deals.show', $deal) }}" class="text-blue-600 hover:underline">Деталі</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $deals->links() }}</div>
        @endif
    </div>
</x-app-layout>
