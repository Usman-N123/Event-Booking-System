<x-app-layout>
    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('events.index') }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>

                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <select name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Cities</option>
                        <option value="Lahore" @selected(request('city') == 'Lahore')>Lahore</option>
                        <option value="Islamabad" @selected(request('city') == 'Islamabad')>Islamabad</option>
                        <option value="Faisalabad" @selected(request('city') == 'Faisalabad')>Faisalabad</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Categories</option>
                        <option value="Concert" @selected(request('category') == 'Concert')>Concert</option>
                        <option value="Workshop" @selected(request('category') == 'Workshop')>Workshop</option>
                        <option value="Webinar" @selected(request('category') == 'Webinar')>Webinar</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-6">
                    <label for="sort_price" class="block text-sm font-medium text-gray-700">Sort by Price</label>
                    <select name="sort_price" id="sort_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Default (Date)</option>
                        <option value="asc" @selected(request('sort_price') == 'asc')>Low to High</option>
                        <option value="desc" @selected(request('sort_price') == 'desc')>High to Low</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none">
                    Apply Filters
                </button>
            </form>
        </aside>

        <section class="flex-grow">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col relative">
                        @if($event->available_seats <= 0)
                            <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow z-10">
                                Sold Out
                            </div>
                        @endif
                        <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover {{ $event->available_seats <= 0 ? 'opacity-70 grayscale' : '' }}">
                        <div class="p-4 flex-grow flex flex-col">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-500 mb-2">{{ $event->city }} &bull; {{ $event->start_date->format('M d, Y') }}</p>
                            <p class="text-xl font-semibold text-indigo-600 mb-4 {{ $event->available_seats <= 0 ? 'grayscale' : '' }}">Rs.{{ number_format($event->price, 2) }}</p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('events.show', $event->id) }}" class="block w-full text-center bg-gray-50 text-indigo-700 hover:bg-gray-100 border border-gray-200 py-2 rounded-md text-sm font-medium transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-lg border border-gray-200 shadow-sm">
                        No events found matching your criteria.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </section>
    </div>
</x-app-layout>