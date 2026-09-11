<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Управління автомобілями</h1>
            <a href="{{ route('admin.cars.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Додати авто</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-gray-900">
            <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-left p-2">Фото</th>
                <th class="text-left p-2">Марка/Модель</th>
                <th class="text-left p-2">Рік</th>
                <th class="text-left p-2">Ціна/день</th>
                <th class="text-left p-2">Статус</th>
                <th class="text-left p-2"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($cars as $car)
                <tr class="border-b">
                    <td class="p-2">
                        @if ($car->photo)
                            <img src="{{ Storage::url($car->photo) }}" class="w-20 h-14 object-cover rounded">
                        @else
                            <div class="w-20 h-14 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">Немає</div>
                        @endif
                    </td>
                    <td class="p-2">{{ $car->brand }} {{ $car->model }}</td>
                    <td class="p-2">{{ $car->year }}</td>
                    <td class="p-2">{{ $car->price_per_day }} грн</td>
                    <td class="p-2">{{ $car->status_label }}</td>
                    <td class="p-2">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.cars.edit', $car) }}" class="text-blue-600 hover:underline">Редагувати</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('admin.cars.maintenance', $car) }}" class="text-purple-600 hover:underline">ТО</a>
                            <span class="text-gray-300">|</span>
                            <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Видалити авто?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Видалити</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $cars->links() }}</div>
    </div>
</x-app-layout>
