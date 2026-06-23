<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">EventHub</a>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('events.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-indigo-500 text-sm font-medium">Browse Events</a>
                </div>
            </div>

            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @auth
                    <div class="flex space-x-4 items-center">
                        <span class="text-sm text-gray-500 mr-4">Welcome, {{ Auth::user()->name }}</span>
                        
                        @if(auth()->user()->role->value === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Admin Panel</a>
                        @elseif(auth()->user()->role->value === 'organizer')
                            <a href="{{ route('organizer.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Organizer Dashboard</a>
                        @else
                            <a href="{{ route('attendee.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">My Tickets</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">Sign Up</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>