<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Bookings for: {{ $event->title }}</h2>
            <p class="mt-1 text-sm text-gray-500">Total Tickets Sold: {{ $bookings->sum('quantity') }}</p>
        </div>
        <a href="{{ route('organizer.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
            &larr; Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tickets</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Ref</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Booked</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $booking->attendee->name }}</div>
                  <div class="text-sm text-gray-500">{{ $booking->attendee->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->quantity }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs.{{ number_format($booking->total_amount, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">{{ $booking->booking_reference }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <!-- Notice how this uses the exact same route the attendee uses! -->
                  <a href="{{ route('attendee.bookings.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900">View Pass</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                    No tickets have been sold for this event yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    
  </div>
</x-app-layout>
