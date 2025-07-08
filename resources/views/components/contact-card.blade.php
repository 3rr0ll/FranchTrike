@props(['icon', 'title', 'value'])
<div class="flex items-center gap-4">
  <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
    <i class="bi {{ $icon }} text-xl"></i>
  </div>
  <div>
    <h4 class="font-semibold mb-1">{{ $title }}</h4>
    <p>{!! $value !!}</p>
  </div>
</div> 