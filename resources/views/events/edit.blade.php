<x-app-layout>
  <div class="max-w-2xl mx-auto">
  <div class="mb-6">
   <a href="{{ route('organizer.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">&larr; Back to Dashboard</a>
  </div>

  <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
   <h2 class="text-xl font-bold text-gray-900 mb-6">Edit Event: {{ $event->title }}</h2>

   <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
     <label class="block text-sm font-medium text-gray-700">Event Title</label>
     <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
     @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
     <label class="block text-sm font-medium text-gray-700">Description</label>
     <textarea name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $event->description) }}</textarea>
     @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-gray-700">Category</label>
      <select name="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
       @foreach(['Concert','Tech','Workshop','Webinar','Conference'] as $cat)
        <option value="{{ $cat }}" @selected(old('category', $event->category) == $cat)>{{ $cat }}</option>
       @endforeach
      </select>
      @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700">City</label>
      <input type="text" name="city" value="{{ old('city', $event->city) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
      @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
     </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div>
      <label class="block text-sm font-medium text-gray-700">Start Date</label>
      <input type="datetime-local" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
      @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
     </div>
     <div>
      <label class="block text-sm font-medium text-gray-700">End Date</label>
      <input type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
      @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
     </div>
    </div>

    <div>
     <label class="block text-sm font-medium text-gray-700">Price (PKR)</label>
     <input type="number" name="price" value="{{ old('price', $event->price) }}" min="0" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
     @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
     <label class="block text-sm font-medium text-gray-700">
      Banner Image <span class="text-gray-400 font-normal">(Leave blank to keep current)</span>
     </label>
     @if($event->banner_path)
      <img src="{{ $event->banner_url }}" class="mt-2 h-20 rounded-md object-cover mb-2" alt="Current banner">
     @endif
     <input type="file" name="banner" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
     @error('banner') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
     <label class="block text-sm font-medium text-gray-700">
      NOC Document (PDF) <span class="text-gray-400 font-normal">(Leave blank to keep current)</span>
     </label>
     <input type="file" name="noc_document" accept="application/pdf" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
     @error('noc_document') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
     <a href="{{ route('organizer.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
     <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
      Save Changes
     </button>
    </div>
   </form>
  </div>
  </div>
</x-app-layout>
