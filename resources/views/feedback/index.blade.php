<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Feedbacks
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- FIXED HEIGHT SCROLLABLE -->
                    <div style="height:400px; overflow-y:scroll; border:1px solid #ddd; padding:1rem;">
                        @if($feedbacks->count() > 0)
                            @foreach($feedbacks as $feedback)
                                <div style="margin-bottom:1rem; padding:1rem; background:#f9f9f9; border-radius:8px;">
                                    <strong>{{ $feedback->user->name ?? 'Anonymous' }}</strong>
                                    <p style="margin:0.5rem 0;">"{{ $feedback->comment }}"</p>
                                    <small>{{ $feedback->created_at->format('F d, Y · h:i A') }}</small>
                                </div>
                            @endforeach
                        @else
                            <p>No feedbacks yet.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
