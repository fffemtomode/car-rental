@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Марка</label>
        <input type="text" name="brand" value="{{ old('brand', $car->brand ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Модель</label>
        <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Рік</label>
        <input type="number" name="year" value="{{ old('year', $car->year ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Ціна оренди/день</label>
        <input type="number" step="0.01" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day ?? '') }}" class="border rounded px-3 py-2 w-full" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Ціна викупу</label>
        <input type="number" step="0.01" name="buyout_price" value="{{ old('buyout_price', $car->buyout_price ?? '') }}" class="border rounded px-3 py-2 w-full">
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Статус</label>
        <select name="status" class="border rounded px-3 py-2 w-full">
            @foreach (['available', 'rented', 'sold', 'maintenance'] as $status)
                <option value="{{ $status }}" @selected(old('status', $car->status ?? '') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Зберегти</button>
</form>
