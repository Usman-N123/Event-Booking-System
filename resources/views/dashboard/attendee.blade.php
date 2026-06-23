<x-app-layout>
  <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-lg leading-6 font-medium text-gray-900">My Ticket History</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref #</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($bookings as $booking)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->event->title }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->event->start_date->format('M d, Y') }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->quantity }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($booking->total_amount, 2) }}</td>
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
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('attendee.bookings.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900">View Pass</a>
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