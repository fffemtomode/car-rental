<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h1>

        @if ($car->photos->count())
            <div x-data="{ active: 0, total: {{ $car->photos->count() }} }" class="relative mb-4">
                @foreach ($car->photos as $i => $photo)
                    <img x-show="active === {{ $i }}" src="{{ Storage::url($photo->path) }}" class="w-full aspect-video object-cover rounded">
                @endforeach

                @if ($car->photos->count() > 1)
                    <button type="button" @click="active = (active - 1 + total) % total" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">‹</button>
                    <button type="button" @click="active = (active + 1) % total" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">›</button>

                    <div class="flex justify-center gap-1 mt-2">
                        @foreach ($car->photos as $i => $photo)
                            <button type="button" @click="active = {{ $i }}" :class="active === {{ $i }} ? 'bg-blue-600' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif ($car->photo)
            <img src="{{ Storage::url($car->photo) }}" class="w-full aspect-video object-cover rounded mb-4">
        @endif

        @if ($car->engine)
            <p class="mb-2 text-gray-900">Двигун: {{ $car->engine }}</p>
        @endif
        @if ($car->mileage)
            <p class="mb-2 text-gray-900">Пробіг: {{ number_format($car->mileage, 0, '', ' ') }} км</p>
        @endif

        <p class="mb-2">Ціна оренди: <strong>{{ $car->price_per_day }} грн/добу</strong></p>
        @if ($car->buyout_price)
            <p class="mb-2">Ціна викупу: <strong>{{ $car->buyout_price }} грн</strong></p>
        @endif
        <p class="mb-4">Статус: {{ $car->status_label }}</p>

        @auth
            @if ($car->status === 'available')
                <div class="flex gap-3 mb-6">
                    <a href="{{ route('deals.create-rental', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Орендувати</a>
                    <a href="{{ route('deals.create-buyout', $car) }}" class="bg-green-600 text-white px-4 py-2 rounded">Викупити</a>
                    <a href="{{ route('deals.create-leasing', $car) }}" class="bg-purple-600 text-white px-4 py-2 rounded">Лізинг</a>
                </div>
            @else
                <p class="mb-6 text-gray-600">Це авто зараз недоступне ({{ $car->status_label }}).</p>
            @endif
        @else
            <div class="mb-6">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Увійти, щоб орендувати</a>
            </div>
        @endauth

        @php
            $periods = $car->deals()->where('type', 'rental')->whereIn('status', ['pending', 'confirmed'])->get();
            $dateStatus = [];
            foreach ($periods as $deal) {
                $period = \Carbon\CarbonPeriod::create($deal->start_date, $deal->end_date);
                foreach ($period as $date) {
                    $dateStatus[$date->format('Y-m-d')] = $deal->user_id === auth()->id() ? 'mine' : 'other';
                }
            }
            $now = \Carbon\Carbon::now();
        @endphp
        <h2 class="text-xl font-semibold mt-6 mb-3 text-gray-900">Доступність</h2>
        <div class="flex gap-4 mb-2 text-sm text-gray-600">
            <span><span class="inline-block w-3 h-3 bg-green-50 border border-green-200 rounded-sm"></span> вільно</span>
            <span><span class="inline-block w-3 h-3 bg-blue-100 rounded-sm"></span> моє бронювання</span>
            <span><span class="inline-block w-3 h-3 bg-red-100 rounded-sm"></span> зайнято іншим</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @for ($i = 0; $i < 3; $i++)
                @php $m = $now->copy()->addMonths($i); @endphp
                @include('partials.calendar-month', ['year' => $m->year, 'month' => $m->month, 'dateStatus' => $dateStatus])
            @endfor
        </div>
        <h2 class="text-xl font-semibold mt-8 mb-3 text-gray-900">Відгуки</h2>

        @php $approvedReviews = $car->reviews()->where('status', 'approved')->latest()->get(); @endphp

        @if ($approvedReviews->isEmpty())
            <p class="text-gray-600 text-sm mb-4">Відгуків про це авто ще немає.</p>
        @else
            <div class="space-y-3 mb-4">
                @foreach ($approvedReviews as $review)
                    <div class="border rounded p-3">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                            <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        </div>
                        <p class="text-gray-700 text-sm mt-1">{{ $review->text }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @auth
            <form method="POST" action="{{ route('reviews.store', $car) }}" class="border rounded-lg p-4">
                @csrf
                <h3 class="font-semibold text-gray-900 mb-2">Залишити відгук</h3>
                <div class="mb-2">
                    <select name="rating" class="border rounded px-3 py-2" required>
                        <option value="">Оцінка</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} {{ str_repeat('★', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <textarea name="text" rows="3" class="border rounded px-3 py-2 w-full mb-2" placeholder="Твій відгук..." required></textarea>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Надіслати</button>
            </form>
        @endauth
    </div>
</x-app-layout>
