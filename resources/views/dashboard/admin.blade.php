<x-app-layout>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Global Revenue</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">Rs.{{ number_format($globalStats['total_revenue'], 2) }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Tickets Booked</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $globalStats['total_bookings'] }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Active Events</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $globalStats['total_events'] }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $globalStats['total_users'] }}</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Organizers</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $globalStats['total_organizers'] }}</dd>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Event Approval Queue</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NOC Document</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendingEvents as $event)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->organizer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 hover:text-indigo-900">
                                <a href="{{ route('admin.events.noc', $event->id) }}" target="_blank">View PDF</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.events.approve', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-4">Approve</button>
                                </form>
                                <form action="{{ route('admin.events.reject', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No events pending approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mt-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Events</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($allEvents as $event)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->organizer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->approval_status->value === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($event->approval_status->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.events.cancel', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Cancel Event</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden mt-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Bookings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->attendee->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->event->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->event->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.{{ number_format($booking->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">{{ $booking->booking_reference }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Confirmed
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>