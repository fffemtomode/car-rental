<footer class="border-t border-gray-100 mt-10 py-6 text-center text-sm text-gray-500">
    <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <span>CarRental — курсовий проєкт, {{ date('Y') }}</span>
        <div class="flex gap-4">
            <a href="{{ \App\Models\Setting::get('telegram_link', 'https://t.me/your_username') }}" target="_blank" class="hover:text-gray-700">Telegram</a>
            <a href="viber://chat?number=%2B{{ preg_replace('/\D/', '', \App\Models\Setting::get('viber_number', '+380000000000')) }}" class="hover:text-gray-700">Viber</a>
            <a href="tel:{{ \App\Models\Setting::get('contact_phone', '+380000000000') }}" class="hover:text-gray-700">{{ \App\Models\Setting::get('contact_phone', '+380 00 000 00 00') }}</a>
        </div>
    </div>
</footer>
