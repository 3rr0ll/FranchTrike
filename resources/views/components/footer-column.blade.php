@props(['title'])
<div>
  <h5 class="font-semibold mb-4">{{ $title }}</h5>
  <div>
    {{ $slot }}
  </div>
</div> 