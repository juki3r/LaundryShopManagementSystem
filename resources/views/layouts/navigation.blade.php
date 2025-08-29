<nav class=" border-r border-gray-200 w-100 min-h-screen flex flex-col justify-start">
    
    <!-- Top: Logo -->
    <div class=" flex items-center justify-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('logo1.png') }}" alt="Logo" style="height: 120px;">
        </a>
    </div>


    {{-- <h2 class="text-center" style="font-size: 14px">
        {{ Auth::user()->name }}
    </h2> --}}

   

    <!-- Middle: Navigation Links -->
    @if(Auth::check() && Auth::user()->role === 'admin')
    <div class="flex flex-col mt-5 justify-start">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="  mb-2">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-light" xmlns="http://www.w3.org/2000/svg" fill="none" 
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5.25a.75.75 0 01-.75-.75V14h-4.5v6.25a.75.75 0 01-.75.75H4a1 1 0 01-1-1V9.75z" />
                </svg>
                <span class="mx-1" style="font-size: 14px">{{ __('Dashboard') }}</span>
            </div>
        </x-nav-link>
        <!-- Orders Nav Link -->
        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-light" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6M5 7h14M5 7a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5z" />
                </svg>
                <span class="mx-1" style="font-size: 14px">{{ __('Orders') }}</span>
            </div>
        </x-nav-link>

        <!-- Orders Nav Link -->
        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-light" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6M5 7h14M5 7a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5z" />
                </svg>
                <span class="mx-1" style="font-size: 14px">{{ __('Orders') }}</span>
            </div>
        </x-nav-link>
    </div>
    @endif

    




    <!-- Bottom: Logout -->
    <div class="p-4 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                class="text-danger">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</nav>
