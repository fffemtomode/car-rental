<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">{{ $client->name }}</h1>
        <p class="text-gray-600 mb-1">{{ $client->email }} · {{ $client->phone ?? 'без телефону' }}</p>
        <a href="{{ route('admin.clients.index') }}" class="text-blue-600 text-sm">← До списку клієнтів</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded my-4">{{ session('success') }}</div>
        @endif

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Історія угод</h2>
        @if ($deals->isEmpty())
            <p class="text-gray-600">Угод ще немає.</p>
        @else
            <table class="w-full border text-gray-900">
                <thead>
                <tr class="border-b bg-gray-100">
                    <th class="text-left p-2">Авто</th>
                    <th class="text-left p-2">Тип</th>
                    <th class="text-left p-2">Статус</th>
                    <th class="text-left p-2">Дата</th>
                    <th class="text-left p-2"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($deals as $deal)
                    <tr class="border-b">
                        <td class="p-2">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                        <td class="p-2">{{ $deal->type_label }}</td>
                        <td class="p-2">{{ $deal->status_label }}</td>
                        <td class="p-2 text-sm">{{ $deal->created_at->format('d.m.Y') }}</td>
                        <td class="p-2"><a href="{{ route('deals.show', $deal) }}" class="text-blue-600">Деталі</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <h2 class="font-semibold mt-6 mb-3 text-gray-900">Нотатки</h2>
        <form method="POST" action="{{ route('admin.clients.notes.store', $client) }}" class="mb-4 flex gap-2">
            @csrf
            <input type="text" name="note" placeholder="Додати нотатку про клієнта..." class="border rounded px-3 py-2 flex-1" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Додати</button>
        </form>

        @if ($notes->isEmpty())
            <p class="text-gray-600">Нотаток ще немає.</p>
        @else
            <div class="space-y-2">
                @foreach ($notes as $note)
                    <div class="border rounded p-3 text-sm">
                        <p class="text-gray-900">{{ $note->note }}</p>
                        <p class="text-gray-400 mt-1">{{ $note->author->name }} · {{ $note->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
