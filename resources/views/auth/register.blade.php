<x-app-layout>
  <div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
      <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Create an Account</h2>

      <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">I want to...</label>
          <div x-data="{ selectedRole: 'attendee' }" class="grid grid-cols-2 gap-4">
            
            <label 
              :class="selectedRole === 'attendee' ? 'border-indigo-600 bg-indigo-100' : 'border-gray-200 bg-white hover:bg-gray-50'"
              class="flex w-full cursor-pointer rounded-lg border-2 p-4 shadow-sm transition-all"
            >
              <input type="radio" name="role" value="attendee" x-model="selectedRole" class="sr-only">
              <span class="flex flex-col">
                <span class="block text-sm font-medium text-gray-900">Buy Tickets</span>
                <span class="mt-1 flex items-center text-sm text-gray-500">Join as Attendee</span>
              </span>
            </label>

            <label 
              :class="selectedRole === 'organizer' ? 'border-indigo-600 bg-indigo-100' : 'border-gray-200 bg-white hover:bg-gray-50'"
              class="flex w-full cursor-pointer rounded-lg border-2 p-4 shadow-sm transition-all"
            >
              <input type="radio" name="role" value="organizer" x-model="selectedRole" class="sr-only">
              <span class="flex flex-col">
                <span class="block text-sm font-medium text-gray-900">Host Events</span>
                <span class="mt-1 flex items-center text-sm text-gray-500">Join as Organizer</span>
              </span>
            </label>

          </div>
          @error('role')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div>
          <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture <span class="text-gray-400 font-normal">(Optional)</span></label>
          <input id="profile_picture" type="file" name="profile_picture" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
          @error('profile_picture')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
          <a class="text-sm text-gray-600 hover:text-indigo-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
            Already registered?
          </a>

          <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Register
          </button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>