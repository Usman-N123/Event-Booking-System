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

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
      <div class="p-5">
        <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $organizerStats['total_bookings'] }}</dd>
      </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200 rounded-lg">
      <div class="p-5">
        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue Earned</dt>
        <dd class="mt-1 text-3xl font-semibold text-gray-900">Rs.{{ number_format($organizerStats['total_revenue'], 2) }}</dd>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
      <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Event</h3>
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700">Event Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Category</label>
              <select name="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Select...</option>
                <option value="Concert" @selected(old('category') == 'Concert')>Concert</option>
                <option value="Tech" @selected(old('category') == 'Tech')>Tech</option>
                <option value="Workshop" @selected(old('category') == 'Workshop')>Workshop</option>
                <option value="Webinar" @selected(old('category') == 'Webinar')>Webinar</option>
                <option value="Conference" @selected(old('category') == 'Conference')>Conference</option>
              </select>
              @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">City</label>
              <input type="text" name="city" value="{{ old('city') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Start Date</label>
              <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">End Date</label>
              <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Price (PKR)</label>
              <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total Seats</label>
              <input type="number" name="total_seats" value="{{ old('total_seats') }}" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              @error('total_seats') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Banner Image</label>
            <input type="file" name="banner" accept="image/*" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('banner') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NOC Document (PDF)</label>
            <input type="file" name="noc_document" accept="application/pdf" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
            @error('noc_document') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit for Approval
          </button>
        </form>
      </div>
    </div>

    <div class="lg:col-span-2">
      <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
          <h3 class="text-lg leading-6 font-medium text-gray-900">My Managed Events</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seats Left</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($myEvents as $event)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->title }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->available_seats }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->approval_status->value === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                      {{ ucfirst($event->approval_status->value) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('events.edit', $event->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No events created yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>