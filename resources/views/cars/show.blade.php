<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h1>

        @if ($car->photos->count())
            <div x-data="{ active: 0, total: {{ $car->photos->count() }} }" class="relative mb-4 rounded-xl overflow-hidden shadow-sm">
                @foreach ($car->photos as $i => $photo)
                    <img x-show="active === {{ $i }}" src="{{ Storage::url($photo->path) }}" class="w-full aspect-video object-cover">
                @endforeach

                @if ($car->photos->count() > 1)
                    <button type="button" @click="active = (active - 1 + total) % total" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">‹</button>
                    <button type="button" @click="active = (active + 1) % total" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 flex items-center justify-center shadow">›</button>

                    <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                        @foreach ($car->photos as $i => $photo)
                            <button type="button" @click="active = {{ $i }}" :class="active === {{ $i }} ? 'bg-white' : 'bg-white/50'" class="w-2 h-2 rounded-full"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif ($car->photo)
            <img src="{{ Storage::url($car->photo) }}" class="w-full aspect-video object-cover rounded-xl mb-4 shadow-sm">
        @else
            <div class="w-full aspect-video bg-gray-100 rounded-xl mb-4 flex items-center justify-center text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16 h-16">
                    <path d="M3 13l1.5-4.5A2 2 0 0 1 6.4 7h11.2a2 2 0 0 1 1.9 1.5L21 13v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5zm2.5 0h13l-1-3H6.5l-1 3zM6 16a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-medium px-2 py-1 rounded-full
                    {{ $car->status === 'available' ? 'bg-green-100 text-green-700' :
                       ($car->status === 'rented' ? 'bg-blue-100 text-blue-700' :
                       ($car->status === 'sold' ? 'bg-gray-200 text-gray-600' : 'bg-orange-100 text-orange-700')) }}">
                    {{ $car->status_label }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                @if ($car->engine)
                    <div><div class="text-gray-400">Двигун</div><div class="text-gray-900 font-medium">{{ $car->engine }}</div></div>
                @endif
                @if ($car->mileage)
                    <div><div class="text-gray-400">Пробіг</div><div class="text-gray-900 font-medium">{{ number_format($car->mileage, 0, '', ' ') }} км</div></div>
                @endif
                @if ($car->color)
                    <div><div class="text-gray-400">Колір</div><div class="text-gray-900 font-medium">{{ $car->color }}</div></div>
                @endif
                @if ($car->plate_number)
                    <div><div class="text-gray-400">Номерний знак</div><div class="text-gray-900 font-medium">{{ $car->plate_number }}</div></div>
                @endif
                <div><div class="text-gray-400">Оренда</div><div class="text-gray-900 font-medium">{{ $car->price_per_day }} грн/добу</div></div>
                @if ($car->buyout_price)
                    <div><div class="text-gray-400">Викуп</div><div class="text-gray-900 font-medium">{{ $car->buyout_price }} грн</div></div>
                @endif
            </div>
        </div>

        @auth
            <div class="flex gap-3 mb-6 flex-wrap">
                @if (!in_array($car->status, ['sold', 'maintenance']))
                    <a href="{{ route('deals.create-rental', $car) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Орендувати</a>
                @endif
                @if ($car->status === 'available')
                    <a href="{{ route('deals.create-buyout', $car) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Викупити</a>
                    <a href="{{ route('deals.create-leasing', $car) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Лізинг</a>
                @endif
            </div>
            @if ($car->status === 'sold')
                <p class="mb-6 text-gray-600">Це авто продано.</p>
            @elseif ($car->status === 'maintenance')
                <p class="mb-6 text-gray-600">Це авто зараз на обслуговуванні.</p>
            @endif
        @else
            <div class="mb-6">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Увійти, щоб орендувати</a>
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
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
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
            <form method="POST" action="{{ route('reviews.store', $car) }}" class="bg-white border border-gray-200 rounded-xl p-4">
                @csrf
                <h3 class="font-semibold text-gray-900 mb-2">Залишити відгук</h3>
                <div class="mb-2">
                    <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Оцінка</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} {{ str_repeat('★', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <textarea name="text" rows="3" class="border border-gray-300 rounded-lg px-3 py-2 w-full mb-2" placeholder="Твій відгук..." required></textarea>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Надіслати</button>
            </form>
        @endauth
    </div>
</x-app-layout>
