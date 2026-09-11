@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="carForm">
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
        <label class="block mb-1 text-gray-900">Двигун</label>
        <input type="text" name="engine" value="{{ old('engine', $car->engine ?? '') }}" placeholder="наприклад, 1.6 TDI, 115 к.с." class="border rounded px-3 py-2 w-full">
    </div>
    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Пробіг (км)</label>
        <input type="number" name="mileage" value="{{ old('mileage', $car->mileage ?? '') }}" class="border rounded px-3 py-2 w-full">
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
            @php
                $statusLabels = [
                    'available' => 'Доступний',
                    'rented' => 'В оренді',
                    'sold' => 'Проданий',
                    'maintenance' => 'На обслуговуванні',
                ];
            @endphp
            @foreach ($statusLabels as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $car->status ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1 text-gray-900">Фото (формат 16:9)</label>

        @if (!empty($car?->photo))
            <p class="text-sm text-gray-600 mb-2">Поточне фото:</p>
            <img src="{{ Storage::url($car->photo) }}" class="w-48 aspect-video object-cover rounded mb-3">
        @endif

        <input type="file" id="photoSourceInput" accept="image/*" class="border rounded px-3 py-2 w-full">

        <div id="cropperWrapper" class="mt-3 hidden">
            <div class="max-w-md">
                <img id="cropperImage" class="max-w-full">
            </div>
            <button type="button" id="cropButton" class="mt-2 bg-gray-700 text-white px-4 py-2 rounded">
                Обрізати та застосувати
            </button>
        </div>

        <div id="croppedPreviewWrapper" class="mt-3 hidden">
            <p class="text-sm text-gray-600 mb-1">Нове фото (буде збережено):</p>
            <img id="croppedPreview" class="w-48 aspect-video object-cover rounded">
        </div>

        <!-- Реальний файл, що піде на сервер -->
        <input type="file" name="photo" id="photoFinalInput" class="hidden">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Зберегти</button>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper = null;

    const sourceInput = document.getElementById('photoSourceInput');
    const cropperWrapper = document.getElementById('cropperWrapper');
    const cropperImage = document.getElementById('cropperImage');
    const cropButton = document.getElementById('cropButton');
    const finalInput = document.getElementById('photoFinalInput');
    const previewWrapper = document.getElementById('croppedPreviewWrapper');
    const preview = document.getElementById('croppedPreview');

    sourceInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            cropperImage.src = event.target.result;
            cropperWrapper.classList.remove('hidden');

            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(cropperImage, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                autoCropArea: 1,
            });
        };
        reader.readAsDataURL(file);
    });

    cropButton.addEventListener('click', function () {
        if (!cropper) return;

        cropper.getCroppedCanvas({ width: 1280, height: 720 }).toBlob(function (blob) {
            const croppedFile = new File([blob], 'car-photo.jpg', { type: 'image/jpeg' });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);
            finalInput.files = dataTransfer.files;

            preview.src = URL.createObjectURL(blob);
            previewWrapper.classList.remove('hidden');
            cropperWrapper.classList.add('hidden');
        }, 'image/jpeg', 0.9);
    });
</script>
