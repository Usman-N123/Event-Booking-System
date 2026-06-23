<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('attendee.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">&larr; Back to Dashboard</a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <!-- Header section with event banner -->
            <div class="relative h-48 md:h-64">
                <img src="{{ asset('storage/' . $booking->event->banner_path) }}" alt="{{ $booking->event->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <span class="px-2 py-1 bg-indigo-500 text-white text-xs font-bold rounded shadow-sm tracking-wide uppercase">{{ $booking->event->category }}</span>
                    <h2 class="mt-2 text-2xl md:text-3xl font-extrabold text-white">{{ $booking->event->title }}</h2>
                    <p class="text-gray-300 mt-1 flex items-center text-sm md:text-base">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $booking->event->city }}
                    </p>
                </div>
            </div>

            <!-- Booking details section -->
            <div class="p-6 md:p-8 bg-white flex flex-col md:flex-row gap-8">
                <!-- Left column: Ticket Info -->
                <div class="flex-grow space-y-6">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-6 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 font-medium uppercase tracking-wider mb-1">Booking Reference</p>
                            <p class="text-2xl font-mono font-bold text-gray-900 bg-gray-50 px-3 py-1 rounded inline-block">{{ $booking->booking_reference }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 font-medium uppercase tracking-wider mb-1">Status</p>
                            @if($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800">Confirmed</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 pt-2">
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-1">Date & Time</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->event->start_date->format('F j, Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $booking->event->start_date->format('g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-1">Attendee Name</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->attendee->name }}</p>
                            <p class="text-sm text-gray-600">{{ $booking->attendee->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-1">Tickets</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->quantity }} x General Admission</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium mb-1">Total Paid</p>
                            <p class="text-base font-semibold text-indigo-600">${{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right column: Pass Picture & QR code placeholder -->
                <div class="w-full md:w-48 flex flex-col items-center justify-center border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 pl-0 md:pl-8">
                    <p class="text-sm text-gray-500 font-medium uppercase tracking-wider mb-3 text-center">Attendee Pass</p>
                    <div class="flex items-center gap-4">
                        <div class="w-full h-22 rounded-xl overflow-hidden shadow-inner bg-gray-50 border-2 border-gray-200 mb-4 flex items-center justify-center">
                            @if($booking->pass_picture_path)
                                <img src="{{ asset('storage/' . $booking->pass_picture_path) }}" alt="Pass Photo" class="w-full h-full object-cover" style="width: 300px; height: 300px;">
                            @else
                                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            @endif
                        </div>
                        <div class="w-full bg-gray-50 p-3 rounded text-center border border-dashed border-gray-300">
                            <!-- Simulated QR Code placeholder -->
                            <div class="w-24 h-24 mx-auto bg-white p-1 border border-gray-200 shadow-sm flex items-center justify-center" style="width: 300px; height: 300px;" >
                                <svg class="w-full h-full text-gray-800" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h-2v2h2v-2zm-2 2h-2v2h2v-2zm2 2h-2v2h2v-2zm2-2h-2v2h2v-2zm-2-2h-2v2h2v-2zm-4 4h-2v2h2v-2zm2 2h-2v2h2v-2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Scan at entry</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-50 px-6 py-4 border-t border-indigo-100 flex items-center justify-between">
                <p class="text-sm text-indigo-800 font-medium">Please present this pass upon arrival at the venue.</p>
                <button onclick="window.print()" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Pass
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
