<x-app-layout>
  <div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
  <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
   <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Reset your password</h2>

   <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div>
     <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
     <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
     @error('email')
      <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
     @enderror
    </div>

    <div>
     <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
     <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
     @error('password')
      <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
     @enderror
    </div>

    <div>
     <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
     <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
     @error('password_confirmation')
      <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
     @enderror
    </div>

    <div class="flex items-center justify-end">
     <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Reset Password
     </button>
    </div>
   </form>
  </div>
  </div>
</x-app-layout>
