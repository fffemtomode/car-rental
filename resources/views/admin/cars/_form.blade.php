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
        <label class="block mb-1 text-gray-900">Фото автомобіля (формат 16:9, можна декілька)</label>

        @if (!empty($car) && $car->photos->count())
            <div class="flex gap-3 mb-3 flex-wrap">
                @foreach ($car->photos as $i => $existingPhoto)
                    <div class="w-28">
                        <div class="relative">
                            <img src="{{ Storage::url($existingPhoto->path) }}" class="w-28 aspect-video object-cover rounded border">
                            @if ($i === 0)
                                <span class="absolute top-1 left-1 bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded">Головне</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-1 px-0.5">
                            <button type="submit" form="moveLeftForm{{ $existingPhoto->id }}"
                                    class="w-7 h-7 flex items-center justify-center rounded border text-gray-600 hover:bg-gray-100 {{ $i === 0 ? 'invisible' : '' }}">
                                ‹
                            </button>
                            <button type="submit" form="deletePhotoForm{{ $existingPhoto->id }}"
                                    class="w-7 h-7 flex items-center justify-center rounded border text-red-600 hover:bg-red-50">
                                🗑
                            </button>
                            <button type="submit" form="moveRightForm{{ $existingPhoto->id }}"
                                    class="w-7 h-7 flex items-center justify-center rounded border text-gray-600 hover:bg-gray-100 {{ $i === $car->photos->count() - 1 ? 'invisible' : '' }}">
                                ›
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div id="newPhotosPreview" class="flex gap-2 mb-3 flex-wrap"></div>

        <input type="file" id="photoSourceInput" accept="image/*" class="border rounded px-3 py-2 w-full">

        <div id="cropperWrapper" class="mt-3 hidden">
            <div class="max-w-md">
                <img id="cropperImage" class="max-w-full">
            </div>
            <button type="button" id="cropButton" class="mt-2 bg-gray-700 text-white px-4 py-2 rounded">
                Додати це фото
            </button>
        </div>

        <input type="file" name="photos[]" id="photoFinalInput" class="hidden" multiple>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Зберегти</button>
</form>

@if (!empty($car) && $car->photos->count())
    @foreach ($car->photos as $existingPhoto)
        <form id="deletePhotoForm{{ $existingPhoto->id }}" method="POST" action="{{ route('admin.cars.photos.destroy', $existingPhoto) }}" onsubmit="return confirm('Видалити фото?')" class="hidden">
            @csrf
            @method('DELETE')
        </form>
        <form id="moveLeftForm{{ $existingPhoto->id }}" method="POST" action="{{ route('admin.cars.photos.move', $existingPhoto) }}" class="hidden">
            @csrf
            <input type="hidden" name="direction" value="left">
        </form>
        <form id="moveRightForm{{ $existingPhoto->id }}" method="POST" action="{{ route('admin.cars.photos.move', $existingPhoto) }}" class="hidden">
            @csrf
            <input type="hidden" name="direction" value="right">
        </form>
    @endforeach
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    window.addEventListener('load', function () {
        let cropper = null;
        let collectedFiles = [];

        const sourceInput = document.getElementById('photoSourceInput');
        const cropperWrapper = document.getElementById('cropperWrapper');
        const cropperImage = document.getElementById('cropperImage');
        const cropButton = document.getElementById('cropButton');
        const finalInput = document.getElementById('photoFinalInput');
        const previewContainer = document.getElementById('newPhotosPreview');

        sourceInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                cropperImage.src = event.target.result;
                cropperWrapper.classList.remove('hidden');

                if (cropper) cropper.destroy();
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
                const croppedFile = new File([blob], 'car-photo-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                collectedFiles.push(croppedFile);

                const dataTransfer = new DataTransfer();
                collectedFiles.forEach(f => dataTransfer.items.add(f));
                finalInput.files = dataTransfer.files;

                const img = document.createElement('img');
                img.src = URL.createObjectURL(blob);
                img.className = 'w-24 aspect-video object-cover rounded';
                previewContainer.appendChild(img);

                cropperWrapper.classList.add('hidden');
                sourceInput.value = '';
            }, 'image/jpeg', 0.9);
        });
    });
</script>
