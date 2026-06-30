{{--
  Flash Toast Component
  Listens for session keys: 'success', 'error', 'warning'
  Auto-dismisses after 5 s with a draining progress bar.
--}}

@foreach ([
  'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-300', 'bar' => 'bg-emerald-400', 'icon_color' => 'text-emerald-500', 'text' => 'text-emerald-800'],
  'error'   => ['bg' => 'bg-red-50',     'border' => 'border-red-300',     'bar' => 'bg-red-400',     'icon_color' => 'text-red-500',     'text' => 'text-red-800'],
  'warning' => ['bg' => 'bg-amber-50',   'border' => 'border-amber-300',   'bar' => 'bg-amber-400',   'icon_color' => 'text-amber-500',   'text' => 'text-amber-800'],
] as $key => $style)
  @if(session()->has($key))
    <div
      x-data="{
        show: true,
        progress: 100,
        timer: null,
        progressTimer: null,
        init() {
          this.timer = setTimeout(() => this.show = false, 5000);
          this.progressTimer = setInterval(() => {
            this.progress -= 2;
            if (this.progress <= 0) {
              clearInterval(this.progressTimer);
            }
          }, 100);
        },
        dismiss() {
          clearTimeout(this.timer);
          clearInterval(this.progressTimer);
          this.show = false;
        }
      }"
      x-show="show"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-x-8"
      x-transition:enter-end="opacity-100 translate-x-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-x-0"
      x-transition:leave-end="opacity-0 translate-x-8"
      class="fixed bottom-4 right-4 z-50 w-80 rounded-lg shadow-xl border overflow-hidden {{ $style['bg'] }} {{ $style['border'] }}"
      role="alert"
    >
      {{-- Draining progress bar --}}
      <div class="h-1 w-full {{ $style['bar'] }} opacity-50"
        :style="'width: ' + progress + '%'"
        style="transition: width 0.1s linear;">
      </div>

      <div class="flex items-start gap-3 p-4">
        {{-- Icon --}}
        <div class="flex-shrink-0 {{ $style['icon_color'] }} mt-0.5">
          @if($key === 'success')
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          @elseif($key === 'error')
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          @else
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          @endif
        </div>

        {{-- Message --}}
        <p class="flex-1 text-sm font-medium {{ $style['text'] }}">
          {{ session($key) }}
        </p>

        {{-- Dismiss button --}}
        <button @click="dismiss()" class="flex-shrink-0 {{ $style['icon_color'] }} hover:opacity-70 focus:outline-none transition-opacity">
          <span class="sr-only">Dismiss</span>
          <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  @endif
@endforeach