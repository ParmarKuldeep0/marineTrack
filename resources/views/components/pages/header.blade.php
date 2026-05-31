
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-neutral-100">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 font-bold text-xl tracking-tight text-neutral-900">
           
            MarineTrack
        </a>

        <!-- Nav pills -->
        <nav class="hidden lg:flex items-center gap-1 bg-neutral-100 rounded-full p-1">
          
            <a href="{{ route('track') }}" 
               @click.prevent="activeTab = 'track'" 
               :class="activeTab === 'track' ? 'nav-pill-active' : 'text-neutral-500 hover:text-neutral-900'"
               class="nav-pill px-5 py-2 text-sm font-medium">
               Track
            </a>
            <a href="{{ route('fleet') }}" 
               @click.prevent="activeTab = 'fleet'" 
               :class="activeTab === 'fleet' ? 'nav-pill-active' : 'text-neutral-500 hover:text-neutral-900'"
               class="nav-pill px-5 py-2 text-sm font-medium">
               Fleet
            </a>
        </nav>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="lg:hidden bg-white border-t">
        <div class="px-6 py-5 space-y-3">
            <div class="flex items-center bg-neutral-100 rounded-full pl-4 pr-2 py-2.5">
                <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Track shipment" class="bg-transparent w-full text-sm focus:outline-none" />
                <button class="bg-black text-white p-2 rounded-full">→</button>
            </div>
            <a href="{{ route('track') }}" @click.prevent="activeTab = 'track'; mobileOpen = false" :class="activeTab === 'track' ? 'bg-black text-white' : 'text-neutral-600 hover:bg-neutral-50'" class="block px-5 py-3 text-sm font-semibold rounded-full  transition">Track</a>
            <a href="{{ route('fleet') }}" @click.prevent="activeTab = 'fleet'; mobileOpen = false" :class="activeTab === 'fleet' ? 'bg-black text-white' : 'text-neutral-600 hover:bg-neutral-50'" class="block px-5 py-3 text-sm font-medium rounded-full transition">Fleet</a>
        </div>
    </div>
</header>