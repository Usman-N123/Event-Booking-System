<x-app-layout>

    {{-- Category badge colour helper --}}
    @php
        $categoryStyles = [
            'Concert'  => 'bg-purple-100 text-purple-800',
            'Workshop' => 'bg-blue-100 text-blue-800',
            'Webinar'  => 'bg-teal-100 text-teal-800',
        ];
        $activeFilters = array_filter(request()->only(['search', 'city', 'category', 'date_from', 'date_to', 'sort_price']));
    @endphp

    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('events.index') }}" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>

                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Title or keyword…">
                </div>

                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <select name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Cities</option>
                        <option value="Lahore"     @selected(request('city') == 'Lahore')>Lahore</option>
                        <option value="Islamabad"  @selected(request('city') == 'Islamabad')>Islamabad</option>
                        <option value="Faisalabad" @selected(request('city') == 'Faisalabad')>Faisalabad</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">All Categories</option>
                        <option value="Concert"  @selected(request('category') == 'Concert')>Concert</option>
                        <option value="Workshop" @selected(request('category') == 'Workshop')>Workshop</option>
                        <option value="Webinar"  @selected(request('category') == 'Webinar')>Webinar</option>
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
                        <option value="asc"  @selected(request('sort_price') == 'asc')>Low to High</option>
                        <option value="desc" @selected(request('sort_price') == 'desc')>High to Low</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none transition-colors">
                    Apply Filters
                </button>

                {{-- Clear Filters: only shown when at least one filter is active --}}
                @if(count($activeFilters) > 0)
                    <a href="{{ route('events.index') }}"
                       class="mt-3 w-full inline-flex items-center justify-center gap-1.5 py-2 px-4 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-red-600 hover:border-red-300 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </a>
                @endif
            </form>
        </aside>

        <section class="flex-grow">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    @php
                        $badgeClass = $categoryStyles[$event->category] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col relative group hover:shadow-md transition-shadow duration-200">
                        {{-- Sold-out overlay badge --}}
                        @if($event->available_seats <= 0)
                            <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow z-10">
                                Sold Out
                            </div>
                        @endif

                        {{-- Banner image --}}
                        <div class="relative overflow-hidden">
                            <img src="{{ $event->banner_url }}"
                                 alt="{{ $event->title }}"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105 {{ $event->available_seats <= 0 ? 'opacity-70 grayscale' : '' }}">
                        </div>

                        <div class="p-4 flex-grow flex flex-col">
                            {{-- Category Badge --}}
                            <div class="mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold tracking-wide {{ $badgeClass }}">
                                    {{ $event->category }}
                                </span>
                            </div>

                            <h4 class="text-base font-bold text-gray-900 mb-1 leading-snug">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-500 mb-2">{{ $event->city }} &bull; {{ $event->start_date->format('M d, Y') }}</p>
                            <p class="text-xl font-semibold text-indigo-600 mb-4">Rs.{{ number_format($event->price, 2) }}</p>

                            <div class="mt-auto">
                                <a href="{{ route('events.show', $event->id) }}"
                                   class="block w-full text-center bg-gray-50 text-indigo-700 hover:bg-indigo-600 hover:text-white border border-gray-200 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <p class="font-medium text-gray-500">No events found</p>
                        <p class="text-sm mt-1">Try adjusting or clearing your filters.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </section>
    </div>
</x-app-layout>