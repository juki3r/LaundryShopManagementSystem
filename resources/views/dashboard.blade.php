<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <h2 class="fs-2">
                Welcome, <strong>{{ Auth::user()->name }}</strong>
            </h2>
        </h2>
    </x-slot>

  @if(Auth::user()->role === 'admin')
    <div class="py-5">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <strong>Total Profit Today</strong>
                                    </div>
                                    <div class="card-body">
                                        0.00
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <strong>Total Claimed Laundry Today</strong>
                                    </div>
                                    <div class="card-body">
                                        0.00
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <strong>Total Orders Today</strong>
                                    </div>
                                    <div class="card-body">
                                        0.00
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <strong>Total Delivered Orders Today</strong>
                                    </div>
                                    <div class="card-body">
                                        0.00
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>               
                </div>
            </div>
        </div>
    </div>
@else

    <div class="py-5">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Hello Client, You must download and install our mobile app. Thanks            
                </div>
            </div>
        </div>
    </div>
    
@endif
</x-app-layout>
