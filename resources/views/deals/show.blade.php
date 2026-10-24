<x-app-layout>
    <div class="max-w-2xl mx-auto py-6 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">{{ $errors->first() }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <a href="{{ route('cars.show', $deal->car) }}" class="block">
                @php $thumb = $deal->car->photos->first()?->path ?? $deal->car->photo; @endphp
                @if ($thumb)
                    <img src="{{ Storage::url($thumb) }}" class="w-full h-56 object-cover hover:opacity-90 transition">
                @else
                    <div class="w-full h-56 bg-gray-100 flex items-center justify-center text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16">
                            <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </div>
                @endif
            </a>
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <a href="{{ route('cars.show', $deal->car) }}" class="text-xl font-bold text-gray-900 hover:text-blue-600">{{ $deal->car->brand }} {{ $deal->car->model }}</a>
                        <p class="text-gray-500 text-sm">Угода #{{ $deal->id }} · {{ $deal->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        {{ $deal->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                           ($deal->status === 'confirmed' ? 'bg-blue-100 text-blue-700' :
                           ($deal->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600')) }}">
                        {{ $deal->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm border-t border-gray-100 pt-4">
                    @if (in_array(auth()->user()->role, ['manager', 'admin']))
                        <div>
                            <div class="text-gray-400">Клієнт</div>
                            <div class="text-gray-900 font-medium">{{ $deal->user->name }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400">Телефон</div>
                            <div class="text-gray-900 font-medium">{{ $deal->user->phone ?? 'без телефону' }}</div>
                        </div>
                    @endif
                    <div>
                        <div class="text-gray-400">Тип угоди</div>
                        <div class="text-gray-900 font-medium">{{ $deal->type_label }}</div>
                    </div>
                    <div>
                        <div class="text-gray-400">Ціна за день</div>
                        <div class="text-gray-900 font-medium">{{ $deal->car->price_per_day }} грн</div>
                    </div>
                    @if ($deal->start_date)
                        <div>
                            <div class="text-gray-400">Період</div>
                            <div class="text-gray-900 font-medium">{{ $deal->start_date }} — {{ $deal->end_date }}</div>
                        </div>
                    @endif
                    @if ($deal->total_price)
                        <div>
                            <div class="text-gray-400">Загальна вартість</div>
                            <div class="text-gray-900 font-medium">{{ $deal->total_price }} грн</div>
                        </div>
                    @endif
                    @if ($deal->mileage_start)
                        <div>
                            <div class="text-gray-400">Пробіг на видачі</div>
                            <div class="text-gray-900 font-medium">{{ number_format($deal->mileage_start, 0, '', ' ') }} км</div>
                        </div>
                    @endif
                    @if ($deal->mileage_end)
                        <div>
                            <div class="text-gray-400">Пробіг на поверненні</div>
                            <div class="text-gray-900 font-medium">{{ number_format($deal->mileage_end, 0, '', ' ') }} км</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($deal->type === 'leasing' && $deal->leasingSchedules->count())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-5 py-3 border-b border-gray-100 font-semibold text-gray-900">Графік платежів</div>
                <table class="w-full text-gray-900">
                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Дата</th>
                        <th class="text-left px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Сума</th>
                        <th class="text-left px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deal->leasingSchedules as $schedule)
                        <tr class="border-b border-gray-100 last:border-0">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($schedule->payment_date)->format('d.m.Y') }}</td>
                            <td class="px-4 py-2">{{ $schedule->amount }} грн</td>
                            <td class="px-4 py-2">{{ $schedule->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @php
            $showActionsBlock = $deal->status === 'pending'
                || $deal->status === 'confirmed'
                || ($deal->status === 'cancelled' && $deal->cancellation_reason)
                || $deal->contract;
        @endphp
        @if ($showActionsBlock)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-3">
            @if ($deal->status === 'pending' && in_array(auth()->user()->role, ['manager', 'admin']))
                <form method="POST" action="{{ route('admin.deals.confirm', $deal) }}" class="flex gap-2 items-start flex-wrap">
                    @csrf
                    @if ($deal->type === 'rental')
                        <input type="number" name="mileage_start" placeholder="Пробіг на видачі (км)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @endif
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Підтвердити угоду</button>
                </form>

                <form method="POST" action="{{ route('admin.deals.reject', $deal) }}" onsubmit="return confirm('Відхилити заявку?')" class="flex gap-2 items-start">
                    @csrf
                    <input type="text" name="reason" placeholder="Причина відхилення (необов'язково)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 whitespace-nowrap">Відхилити</button>
                </form>
            @elseif ($deal->status === 'pending')
                <p class="text-gray-600">Очікує підтвердження менеджером.</p>
            @endif

            @if ($deal->status === 'confirmed' && in_array(auth()->user()->role, ['manager', 'admin']))
                <form method="POST" action="{{ route('admin.deals.complete', $deal) }}" class="flex gap-2 items-start flex-wrap" onsubmit="return confirm('Завершити угоду?')">
                    @csrf
                    <input type="number" name="mileage_end" placeholder="Пробіг на поверненні (км)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 whitespace-nowrap">Завершити угоду</button>
                </form>
            @endif

            @if (in_array($deal->status, ['pending', 'confirmed']) && $deal->user_id === auth()->id())
                <form method="POST" action="{{ route('deals.cancel', $deal) }}" class="flex gap-2 items-start" onsubmit="return confirm('Скасувати угоду?')">
                    @csrf
                    <input type="text" name="reason" placeholder="Причина скасування (необов'язково)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm whitespace-nowrap">Скасувати угоду</button>
                </form>
            @endif

            @if ($deal->status === 'cancelled' && $deal->cancellation_reason)
                <p class="text-gray-600 text-sm">Причина скасування: {{ $deal->cancellation_reason }}</p>
            @endif

            @if ($deal->contract)
                <a href="{{ Storage::url($deal->contract->file_path) }}" target="_blank" class="inline-block text-blue-600 hover:underline font-medium">
                    📄 Завантажити договір (PDF)
                </a>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>
