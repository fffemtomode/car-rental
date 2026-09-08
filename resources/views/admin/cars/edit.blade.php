<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Редагувати автомобіль</h1>

        @include('admin.cars._form', ['action' => route('admin.cars.update', $car), 'method' => 'PUT', 'car' => $car])
    </div>
</x-app-layout>
