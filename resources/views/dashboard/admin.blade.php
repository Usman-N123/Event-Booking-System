<x-app-layout>

    <div x-data="{
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'overview',
        setTab(tab) {
            const params = new URLSearchParams(window.location.search);
            params.set('tab', tab);
            params.delete('users_page');
            params.delete('events_page');
            params.delete('organizers_page');
            window.location.href = '{{ route('admin.dashboard') }}?' + params.toString();
        }
    }">

        {{-- ===== Tab Bar ===== --}}
        <div class="border-b border-gray-200 mb-8">
            <nav class="-mb-px flex space-x-1" aria-label="Tabs">
                @foreach ([
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
        ['id' => 'events', 'label' => 'Events', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5'],
        ['id' => 'users', 'label' => 'Users', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
        ['id' => 'organizers', 'label' => 'Organizers', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z'],
    ] as $tab)
                    <button id="tab-{{ $tab['id'] }}" @click="setTab('{{ $tab['id'] }}')"
                        :class="activeTab === '{{ $tab['id'] }}'
                            ?
                            'border-indigo-600 text-indigo-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="group inline-flex items-center gap-2 border-b-2 py-4 px-4 text-sm font-medium transition-colors duration-150 focus:outline-none"
                        type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}" />
                        </svg>
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- ===================================================================
    TAB 1: OVERVIEW
  ================================================================== --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
                @foreach ([['label' => 'Total Revenue', 'value' => 'Rs.' . number_format($globalStats['total_revenue'], 2), 'color' => 'indigo'], ['label' => 'Total Bookings', 'value' => $globalStats['total_bookings'], 'color' => 'blue'], ['label' => 'Active Events', 'value' => $globalStats['total_events'], 'color' => 'emerald'], ['label' => 'Pending Events', 'value' => $globalStats['pending_approvals'], 'color' => 'amber'], ['label' => 'Total Users', 'value' => $globalStats['total_users'], 'color' => 'purple'], ['label' => 'Organizers', 'value' => $globalStats['total_organizers'], 'color' => 'rose']] as $stat)
                    <div
                        class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 hover:shadow-md transition-shadow">
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide truncate">
                            {{ $stat['label'] }}</dt>
                        <dd class="mt-2 text-2xl font-bold text-gray-900">{{ $stat['value'] }}</dd>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-gray-400 text-right">
                Stats cached · last updated {{ \Carbon\Carbon::parse($globalStats['last_updated'])->diffForHumans() }}
            </p>
        </div>

        {{-- ===================================================================
    TAB 2: EVENTS
  ================================================================== --}}
        <div x-show="activeTab === 'events'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Pending Approval Queue --}}
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                        {{ $pendingEvents->total() }} pending
                    </span>
                    <h3 class="text-base font-semibold text-gray-900">Event Approval Queue</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Organizer</th>
                                <th class="px-6 py-3 text-left">Event</th>
                                <th class="px-6 py-3 text-left">NOC</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($pendingEvents as $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $event->organizer?->name ?? 'Deleted Organizer' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $event->title }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.events.noc', $event->id) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">View PDF</a>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('admin.events.approve', $event->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.events.reject', $event->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">No events pending
                                        approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- All Events — Filter Bar --}}
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-row items-center gap-3">
                    <h3 class="text-base font-semibold text-gray-900 flex-1">All Events</h3>
                    <form id="events-filter-form" method="GET" action="{{ route('admin.dashboard') }}"
                        class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="tab" value="events">
                        <div class="relative">
                            <svg class="pointer-events-none absolute top-[10px] right-[10px] h-4 w-4 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="top: 10px; right: 10px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input id="events-search" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search events…"
                                class="pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-48">
                        </div>
                        <select id="events-status-filter" name="status"
                            class="text-sm border border-gray-300 rounded-md py-1.5 pl-2 pr-7 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                        </select>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit"
                            class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">Filter</button>
                        @if (request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('admin.dashboard', ['tab' => 'events']) }}"
                                class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Clear</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Organizer</th>
                                <th class="px-6 py-3 text-left">Event</th>
                                <th class="px-6 py-3 text-left">Category</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($allEvents as $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $event->organizer?->name ?? 'Deleted Organizer' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $event->title }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $event->category }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusMap = [
                                                'approved' => 'bg-emerald-100 text-emerald-800',
                                                'draft' => 'bg-amber-100 text-amber-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                            $cls =
                                                $statusMap[$event->approval_status->value] ??
                                                'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">
                                            {{ ucfirst($event->approval_status->value) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.events.cancel', $event->id) }}" method="POST"
                                            class="inline" x-data
                                            @submit.prevent="if(confirm('Cancel this event?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 text-xs font-medium">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">No events found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($allEvents->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">{{ $allEvents->links() }}</div>
                @endif
            </div>
        </div>

        {{-- ===================================================================
    TAB 3: USERS
  ================================================================== --}}
        <div x-show="activeTab === 'users'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-row items-center gap-3">
                    <h3 class="text-base font-semibold text-gray-900 flex-1">All Users</h3>
                    <form id="users-filter-form" method="GET" action="{{ route('admin.dashboard') }}"
                        class="flex flex-row items-center gap-2">
                        <input type="hidden" name="tab" value="users">
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-2.5 top-2.5 h-4 w-4 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="top: 10px; right: 10px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input id="users-search" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search name or email…"
                                class="pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-52">
                        </div>
                        <select id="users-role-filter" name="role"
                            class="text-sm border border-gray-300 rounded-md py-1.5 pl-2 pr-7 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Roles</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="organizer" @selected(request('role') === 'organizer')>Organizer</option>
                            <option value="attendee" @selected(request('role') === 'attendee')>Attendee</option>
                        </select>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit"
                            class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">Filter</button>
                        @if (request()->hasAny(['search', 'role', 'date_from', 'date_to']))
                            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}"
                                class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Clear</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-left">Approved</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($allUsers as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $roleMap = [
                                                'admin' => 'bg-purple-100 text-purple-800',
                                                'organizer' => 'bg-blue-100 text-blue-800',
                                                'attendee' => 'bg-gray-100 text-gray-700',
                                            ];
                                            $roleCls = $roleMap[$user->role->value] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $roleCls }}">{{ ucfirst($user->role->value) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->role->value === 'organizer')
                                            @if ($user->is_approved)
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Yes</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Pending</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($user->role->value !== 'admin')
                                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                method="POST" class="inline" x-data
                                                @submit.prevent="if(confirm('Delete user {{ addslashes($user->name) }}? This cannot be undone.')) $el.submit()">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 text-xs font-medium">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 text-xs">Protected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($allUsers->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">{{ $allUsers->links() }}</div>
                @endif
            </div>
        </div>

        {{-- ===================================================================
    TAB 4: ORGANIZERS
  ================================================================== --}}
        <div x-show="activeTab === 'organizers'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-row items-center gap-3">
                    <div class="flex items-center gap-3 flex-1">
                        @if ($pendingOrganizers->total() > 0)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                {{ $pendingOrganizers->total() }} pending
                            </span>
                        @endif
                        <h3 class="text-base font-semibold text-gray-900">Pending Organizer Approvals</h3>
                    </div>
                    <form id="organizers-filter-form" method="GET" action="{{ route('admin.dashboard') }}"
                        class="flex flex-row items-center gap-2">
                        <input type="hidden" name="tab" value="organizers">
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-2.5 top-2.5 h-4 w-4 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="top: 10px; right: 10px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <input id="organizers-search" type="text" name="search"
                                value="{{ request('search') }}" placeholder="Search name or email…"
                                class="pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-52">
                        </div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="text-sm border border-gray-300 rounded-md py-1.5 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit"
                            class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">Filter</button>
                        @if (request()->hasAny(['search', 'date_from', 'date_to']))
                            <a href="{{ route('admin.dashboard', ['tab' => 'organizers']) }}"
                                class="inline-flex items-center px-3 py-3 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Clear</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Registered</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($pendingOrganizers as $organizer)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $organizer->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $organizer->email }}</td>
                                    <td class="px-6 py-4 text-gray-400">{{ $organizer->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('admin.users.approve', $organizer->id) }}"
                                            method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.users.reject', $organizer->id) }}"
                                            method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                        </svg>
                                        <p class="text-gray-400 font-medium">All caught up!</p>
                                        <p class="text-gray-400 text-xs mt-1">No organizers are waiting for approval.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($pendingOrganizers->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">{{ $pendingOrganizers->links() }}</div>
                @endif
            </div>
        </div>

        {{-- ===================================================================
    GLOBAL: All Bookings (Overview tab only)
  ================================================================== --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            class="mt-8">
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Recent Bookings</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Attendee</th>
                                <th class="px-6 py-3 text-left">Event</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Qty</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Ref #</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $booking->attendee?->name ?? 'Deleted User' }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $booking->event?->title ?? 'Deleted Event' }}</td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $booking->event?->start_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $booking->quantity }}</td>
                                    <td class="px-6 py-4 text-gray-500">
                                        Rs.{{ number_format($booking->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                                        {{ $booking->booking_reference }}</td>
                                    <td class="px-6 py-4">
                                        @if ($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Confirmed</span>
                                        @else
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($bookings->hasPages())
                    <div class="px-6 py-3 border-t border-gray-200">{{ $bookings->links() }}</div>
                @endif
            </div>
        </div>

    </div>{{-- end x-data --}}

</x-app-layout>
