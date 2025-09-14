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
                                    
                                    <!-- User + Rating -->
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <strong>{{ $feedback->user->name ?? 'Anonymous' }}</strong>
                                        <div style="display:flex; gap:2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $feedback->rating)
                                                    <!-- Filled star -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                         viewBox="0 0 20 20" 
                                                         fill="currentColor" 
                                                         style="width:20px; height:20px; color:gold;">
                                                        <path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.955L10 0l2.952 5.955 
                                                        6.561.955-4.757 4.635 1.122 6.545z"/>
                                                    </svg>
                                                @else
                                                    <!-- Empty star -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                         viewBox="0 0 20 20" 
                                                         fill="currentColor" 
                                                         style="width:20px; height:20px; color:#ddd;">
                                                        <path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.955L10 0l2.952 5.955 
                                                        6.561.955-4.757 4.635 1.122 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Feedback message -->
                                    <p style="margin:0.5rem 0; font-style:italic;">
                                        "{{ $feedback->comment }}"
                                    </p>

                                    <!-- Date -->
                                    <small style="color:#777;">
                                        {{ $feedback->created_at->format('F d, Y · h:i A') }}
                                    </small>
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
