<x-app-layout>
    <div class="max-w-xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Налаштування контактів</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Посилання на Telegram</label>
                    <input type="text" name="telegram_link" value="{{ old('telegram_link', $settings['telegram_link']) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-white">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Номер Viber</label>
                    <input type="text" name="viber_number" value="{{ old('viber_number', $settings['viber_number']) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-white">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-900">Контактний телефон</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-white">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Зберегти</button>
            </form>
        </div>
    </div>
</x-app-layout>
