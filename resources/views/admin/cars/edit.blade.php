<x-app-layout>
    <div class="max-w-2xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">Редагувати: {{ $car->brand }} {{ $car->model }}</h1>
        <a href="{{ route('admin.cars.index') }}" class="text-blue-600 text-sm">← До списку авто</a>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mt-4">
            @include('admin.cars._form', ['action' => route('admin.cars.update', $car), 'method' => 'PUT', 'car' => $car])
        </div>
    </div>
</x-app-layout>
