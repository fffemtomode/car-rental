<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Клієнти</h1>

        <form method="GET" class="mb-4 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Пошук за іменем, email, телефоном" class="border border-gray-300 rounded-lg px-3 py-2 flex-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Пошук</button>
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-gray-900">
                <thead>
                <tr class="bg-gray-50 border-b-2 border-gray-200">
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Ім'я</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Телефон</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Угод</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clients as $client)
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $client->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $client->email }}</td>
                        <td class="px-4 py-3 text-sm">{{ $client->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded-full">{{ $client->deals_count }}</span>
                        </td>
                        <td class="px-4 py-3"><a href="{{ route('admin.clients.show', $client) }}" class="text-blue-600 hover:underline">Деталі</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $clients->links() }}</div>
    </div>
</x-app-layout>
