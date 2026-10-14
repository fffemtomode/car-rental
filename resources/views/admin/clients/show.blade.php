<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">{{ $client->name }}</h1>
        <p class="text-gray-600 mb-1">{{ $client->email }} · {{ $client->phone ?? 'без телефону' }}</p>
        <a href="{{ route('admin.clients.index') }}" class="text-blue-600 text-sm">← До списку клієнтів</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg my-4">{{ session('success') }}</div>
        @endif

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Історія угод</h2>
        @if ($deals->isEmpty())
            <p class="text-gray-600">Угод ще немає.</p>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <table class="w-full text-gray-900">
                    <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Авто</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Тип</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Дата</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deals as $deal)
                        <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                            <td class="px-4 py-3">{{ $deal->type_label }}</td>
                            <td class="px-4 py-3">{{ $deal->status_label }}</td>
                            <td class="px-4 py-3 text-sm">{{ $deal->created_at->format('d.m.Y') }}</td>
                            <td class="px-4 py-3"><a href="{{ route('deals.show', $deal) }}" class="text-blue-600 hover:underline">Деталі</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Нотатки</h2>
        <form method="POST" action="{{ route('admin.clients.notes.store', $client) }}" class="mb-4 flex gap-2">
            @csrf
            <input type="text" name="note" placeholder="Додати нотатку про клієнта..." class="border border-gray-300 rounded-lg px-3 py-2 flex-1" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Додати</button>
        </form>

        @if ($notes->isEmpty())
            <p class="text-gray-600">Нотаток ще немає.</p>
        @else
            <div class="space-y-2">
                @foreach ($notes as $note)
                    <div class="bg-white border border-gray-200 rounded-lg p-3 text-sm">
                        <p class="text-gray-900">{{ $note->note }}</p>
                        <p class="text-gray-400 mt-1">{{ $note->author->name }} · {{ $note->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
