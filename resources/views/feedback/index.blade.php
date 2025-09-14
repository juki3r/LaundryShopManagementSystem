<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Feedbacks
        </h2>
    </x-slot>

    <div class="py-5">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Fixed height container -->
            <div class="p-6 text-gray-900">
                <div class="h-[70vh] overflow-y-auto pr-3">
                    @if($feedbacks->count() > 0)
                        <div class="space-y-6">
                            @foreach($feedbacks as $feedback)
                                <div class="border rounded-lg p-5 shadow-sm bg-gray-50 hover:shadow-md transition">
                                    <div class="flex items-center space-x-3">
                                        <!-- Avatar with first letter -->
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-yellow-500 text-white font-bold">
                                            {{ strtoupper(substr($feedback->user->name ?? 'A', 0, 1)) }}
                                        </div>

                                        <div class="flex-1">
                                            <!-- User + Rating -->
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-gray-800">
                                                    {{ $feedback->user->name ?? 'Anonymous' }}
                                                </p>

                                                <div class="flex items-center space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $feedback->rating)
                                                            <svg class="w-5 h-5 text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.955L10 0l2.952 5.955 6.561.955-4.757 4.635 1.122 6.545z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.955L10 0l2.952 5.955 6.561.955-4.757 4.635 1.122 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Feedback message -->
                                    <blockquote class="mt-3 border-l-4 border-yellow-400 pl-3 text-gray-700 italic leading-relaxed">
                                        "{{ $feedback->comment }}"
                                    </blockquote>

                                    <!-- Date -->
                                    <p class="text-sm text-gray-400 mt-2">
                                        {{ $feedback->created_at->format('F d, Y · h:i A') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No feedbacks yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
