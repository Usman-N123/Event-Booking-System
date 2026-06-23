<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="w-full h-64 md:h-96 relative">
            <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover {{ $event->available_seats <= 0 ? 'opacity-70 grayscale' : '' }}">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-60"></div>
            @if($event->available_seats <= 0)
                <div class="absolute top-4 right-4 bg-red-600 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg z-10">
                    Sold Out
                </div>
            @endif
            <div class="absolute bottom-0 left-0 p-6 md:p-10">
                <span class="px-3 py-1 bg-indigo-600 text-white text-sm font-semibold rounded-full shadow-sm">{{ $event->category }}</span>
                <h1 class="mt-4 text-3xl md:text-5xl font-extrabold text-white tracking-tight">{{ $event->title }}</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6 md:p-10">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Event Details</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Date & Time</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $event->start_date->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Location</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $event->city }}</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-4 mb-4">Secure Your Spot</h3>
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-gray-500">Price per ticket</span>
                        <span class="text-2xl font-extrabold text-indigo-600">Rs.{{ number_format($event->price, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center mb-6 text-sm">
                        <span class="text-gray-500">Availability</span>
                        <span class="font-medium {{ $event->available_seats > 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $event->available_seats }} seats remaining
                        </span>
                    </div>

                    @auth
                        @if(auth()->user()->role->value === 'attendee')
                            @if($event->available_seats > 0)
                                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" x-data="{ quantity: 1, price: {{ $event->price }} }">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <div class="mb-4">
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Select Quantity</label>
                                        <input type="number"
                                               name="quantity"
                                               id="quantity"
                                               x-model="quantity"
                                               min="1"
                                               max="{{ $event->available_seats }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>

                                    <div class="mb-6">
                                        <label for="pass_picture" class="block text-sm font-medium text-gray-700 mb-1">
                                            Pass Photo <span class="text-gray-400 font-normal">(Optional)</span>
                                        </label>
                                        <input id="pass_picture" type="file" name="pass_picture" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="mt-1 text-xs text-gray-400">Leave blank to use your profile picture.</p>
                                        @error('pass_picture')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-between items-center mb-6 border-t border-gray-200 pt-4">
                                        <span class="text-gray-900 font-bold">Total</span>
                                        <span class="text-xl font-extrabold text-gray-900" x-text="'$' + (quantity * price).toFixed(2)"></span>
                                    </div>

                                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Confirm Booking
                                    </button>
                                </form>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-md p-4 text-center">
                                    <span class="text-red-800 font-semibold">Sold Out</span>
                                </div>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 text-center">
                                <p class="text-sm text-yellow-800 font-medium">Ticket purchasing is available to attendee accounts only.</p>
                            </div>
                        @endif
                    @else
                        <div class="bg-indigo-50 border border-indigo-100 rounded-md p-4 text-center">
                            <p class="text-sm text-indigo-800 mb-3">You must be logged in to book tickets.</p>
                            <a href="{{ route('login') }}" class="block w-full bg-white text-indigo-600 border border-indigo-200 py-2 px-4 rounded-md text-sm font-medium hover:bg-indigo-50 transition-colors">
                                Log In to Book
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>