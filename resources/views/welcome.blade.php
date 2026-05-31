<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OceanTrack – Premium Shipping</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
   @include('components.pages.header')   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-neutral-900 antialiased" x-data="{ activeTab: 'track', mobileOpen: false }">


<main class="max-w-6xl mx-auto px-6 lg:px-8 pt-24 pb-32 text-center">
    <span class="inline-flex items-center gap-2 bg-neutral-100 text-neutral-600 text-sm font-semibold px-5 py-2 rounded-full mb-8">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        Live tracking · 24/7
    </span>
    <h1 class="text-5xl md:text-7xl font-extrabold text-neutral-900 mb-6 tracking-tight leading-[1.1]">
        Ship smarter.
        <br />
        <span class="text-neutral-400">Track better.</span>
    </h1>
    <p class="text-lg text-neutral-500 max-w-lg mx-auto mb-10">
        Real‑time ocean visibility, predictive ETAs, and instant alerts.
    </p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('track') }}" class="bg-black text-white px-7 py-3.5 rounded-full text-sm font-semibold hover:bg-neutral-800 transition">Start tracking →</a>
        <a href="{{ route('about') }}" class="bg-white border border-neutral-200 text-neutral-700 px-7 py-3.5 rounded-full text-sm font-semibold hover:bg-neutral-50 transition">Learn more</a>
    </div>
    
    <!-- Active tab indicator -->
    <div class="mt-12 text-sm text-neutral-400">
        Active tab: <span class="font-semibold text-black" x-text="activeTab"></span>
    </div>
</main>
<
</body>
</html>