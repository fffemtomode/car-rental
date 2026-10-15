<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Відгуки</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4 flex gap-3">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-900 text-sm">
                <option value="">Усі статуси</option>
                <option value="pending" @selected(request('status') === 'pending')>На розгляді</option>
                <option value="approved" @selected(request('status') === 'approved')>Опубліковано</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Відхилено</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Фільтрувати</button>
        </form>

        @if ($reviews->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl p-10 text-center text-gray-500">Відгуків ще немає.</div>
        @else
            <div class="space-y-3">
                @foreach ($reviews as $review)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                @if ($review->car)
                                    <span class="text-gray-500 text-sm"> — {{ $review->car->brand }} {{ $review->car->model }}</span>
                                @endif
                                <span class="text-yellow-500 ml-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            </div>
                            <span class="text-xs font-medium px-2 py-1 rounded-full {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : ($review->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800') }}">
                                {{ $review->status_label }}
                            </span>
                        </div>
                        <p class="text-gray-700 text-sm mt-2">{{ $review->text }}</p>

                        @if ($review->status === 'pending')
                            <div class="flex gap-3 mt-3">
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                    @csrf
                                    <button type="submit" class="text-green-700 text-sm font-medium hover:underline">Опублікувати</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                    @csrf
                                    <button type="submit" class="text-red-600 text-sm font-medium hover:underline">Відхилити</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">{{ $reviews->links() }}</div>
    </div>
</x-app-layout>
