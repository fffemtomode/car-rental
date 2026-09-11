<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Усі заявки</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-gray-900">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left p-2">Клієнт</th>
                <th class="text-left p-2">Авто</th>
                <th class="text-left p-2">Тип</th>
                <th class="text-left p-2">Статус</th>
                <th class="text-left p-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($deals as $deal)
                <tr class="border-b">
                    <td class="p-2">{{ $deal->user->name }}</td>
                    <td class="p-2">{{ $deal->car->brand }} {{ $deal->car->model }}</td>
                    <td class="p-2">{{ $deal->type_label }}</td>
                    <td class="p-2">{{ $deal->status_label }}</td>
                    <td class="p-2">
                        <a href="{{ route('deals.show', $deal) }}" class="text-blue-600">Деталі</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $deals->links() }}</div>
    </div>
</x-app-layout>
