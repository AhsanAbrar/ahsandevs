@if(option('app_logo'))
  <div class="flex h-12 items-center">
    <img src="/{{ option('app_logo') }}" style="max-height: 2rem" />
  </div>
@else
  <p class="flex h-12 items-center">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-500">
      <path d="M16.5 6a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v7.5a3 3 0 0 0 3 3v-6A4.5 4.5 0 0 1 10.5 6h6Z" />
      <path d="M18 7.5a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-7.5a3 3 0 0 1-3-3v-7.5a3 3 0 0 1 3-3H18Z" />
    </svg>

    <span class="text-2xl font-semibold text-gray-800 ltr:ml-2.5 rtl:mr-2.5">
      {{ config('app.name') }}
    </span>
  </p>
@endif
