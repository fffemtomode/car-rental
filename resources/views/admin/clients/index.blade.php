<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Клієнти</h1>

        <form method="GET" class="mb-4 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Пошук за іменем, email, телефоном" class="border rounded px-3 py-2 flex-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Пошук</button>
        </form>

        <table class="w-full border text-gray-900">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left p-2">Ім'я</th>
                <th class="text-left p-2">Email</th>
                <th class="text-left p-2">Телефон</th>
                <th class="text-left p-2">Угод</th>
                <th class="text-left p-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($clients as $client)
                <tr class="border-b">
                    <td class="p-2">{{ $client->name }}</td>
                    <td class="p-2">{{ $client->email }}</td>
                    <td class="p-2">{{ $client->phone ?? '—' }}</td>
                    <td class="p-2">{{ $client->deals_count }}</td>
                    <td class="p-2"><a href="{{ route('admin.clients.show', $client) }}" class="text-blue-600">Деталі</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $clients->links() }}</div>
    </div>
</x-app-layout>
