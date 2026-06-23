<x-app-layout>
  <div class="min-h-[70vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
      <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Forgot your password?</h2>
      <p class="text-sm text-gray-500 text-center mb-8">No problem. Enter your email and we'll send you a reset link.</p>

      @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
          <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center justify-between">
          <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 focus:outline-none">
            Back to login
          </a>
          <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Email Reset Link
          </button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
