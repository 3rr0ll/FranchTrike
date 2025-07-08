@props(['icon', 'title', 'description'])
<div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
  <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
    <i class="bi {{ $icon }} text-2xl text-primary-navy group-hover:text-white"></i>
  </div>
  <h4 class="text-xl font-semibold mb-4">{{ $title }}</h4>
  <p class="text-gray-600 group-hover:text-gray-300">{{ $description }}</p>
</div> 