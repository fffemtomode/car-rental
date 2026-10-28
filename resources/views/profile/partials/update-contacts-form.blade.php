<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Контакти сайту
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Telegram, Viber і телефон — показуються у футері сайту для всіх відвідувачів.
        </p>
    </header>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mt-4 text-sm">{{ session('success') }}</div>
    @endif

    <form method="post" action="{{ route('admin.settings.update') }}" class="mt-6 space-y-6">
        @csrf
        <div>
            <x-input-label for="telegram_link" value="Посилання на Telegram" />
            <x-text-input id="telegram_link" name="telegram_link" type="text" class="mt-1 block w-full" :value="old('telegram_link', $contactSettings['telegram_link'])" />
        </div>
        <div>
            <x-input-label for="viber_number" value="Номер Viber" />
            <x-text-input id="viber_number" name="viber_number" type="text" class="mt-1 block w-full" :value="old('viber_number', $contactSettings['viber_number'])" />
        </div>
        <div>
            <x-input-label for="contact_phone" value="Контактний телефон" />
            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $contactSettings['contact_phone'])" />
        </div>
        <x-primary-button>Зберегти контакти</x-primary-button>
    </form>
</section>
