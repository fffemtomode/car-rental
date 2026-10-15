<x-app-layout>
    <div class="max-w-2xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-1 text-gray-900">Додати автомобіль</h1>
        <p class="text-gray-500 text-sm mb-6">Заповни основні дані, завантаж фото — решту завжди можна відредагувати пізніше.</p>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            @include('admin.cars._form', ['action' => route('admin.cars.store'), 'method' => 'POST', 'car' => null])
        </div>
    </div>
</x-app-layout>
