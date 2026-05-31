<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management – MarineTrack Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @include('components.pages.header')   
</head>
<body class="text-slate-700">

    <div class="border-b border-slate-200 bg-white/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                
               
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-2 text-sm">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400 pulse"></span></span>
                    <span id="connection-status" class="text-emerald-500 font-semibold text-xs">CONNECTING...</span>
                </span>
                <span class="text-xs text-slate-400">|</span>
                <span id="vessel-count-display" class="text-xs text-slate-500">0 vessels</span>
                <a href="/track" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-black rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:shadow-xl transition">Live Map</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-6 pb-3">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            <div class="stat-card"><p class="text-[10px] text-slate-400 uppercase font-semibold mb-1">Total</p><p id="stat-total" class="text-2xl font-extrabold text-slate-800">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-purple-500 uppercase font-semibold mb-1">Container</p><p id="stat-container" class="text-2xl font-extrabold text-purple-600">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-blue-500 uppercase font-semibold mb-1">Cargo</p><p id="stat-cargo" class="text-2xl font-extrabold text-blue-600">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-amber-500 uppercase font-semibold mb-1">Tanker</p><p id="stat-tanker" class="text-2xl font-extrabold text-amber-600">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-pink-500 uppercase font-semibold mb-1">Cruise</p><p id="stat-cruise" class="text-2xl font-extrabold text-pink-600">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-emerald-500 uppercase font-semibold mb-1">Bulk</p><p id="stat-bulk" class="text-2xl font-extrabold text-emerald-600">0</p></div>
            <div class="stat-card"><p class="text-[10px] text-teal-500 uppercase font-semibold mb-1">Tug/Fish</p><p id="stat-other" class="text-2xl font-extrabold text-teal-600">0</p></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-3">
        <div class="card p-4 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]"><input type="text" id="search" oninput="renderFleet()" placeholder="Search vessel name or MMSI..." class="w-full pl-10"></div>
            <select id="type-filter" onchange="renderFleet()"><option value="all">All Types</option><option value="Container">Container</option><option value="Cargo">Cargo</option><option value="Tanker">Tanker</option><option value="Cruise">Cruise</option><option value="Bulk">Bulk</option><option value="Tug">Tug</option><option value="Fishing">Fishing</option></select>
            <select id="speed-filter" onchange="renderFleet()"><option value="all">All Speeds</option><option value="moving">Moving (>0.5kn)</option><option value="fast">Fast (>10kn)</option><option value="stationary">Stationary</option></select>
            <select id="sort-by" onchange="renderFleet()"><option value="speed">Sort: Speed</option><option value="name">Sort: Name</option><option value="type">Sort: Type</option></select>
            <select id="per-page" onchange="changePerPage()"><option value="10">10/page</option><option value="25" selected>25/page</option><option value="50">50/page</option></select>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-6">
        <div class="card overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-200 px-5 py-3 grid grid-cols-12 gap-4 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <div class="col-span-1">#</div><div class="col-span-3">Vessel</div><div class="col-span-2">Type</div><div class="col-span-1">MMSI</div><div class="col-span-1 text-center">Speed</div><div class="col-span-1 text-center">Course</div><div class="col-span-2">Destination</div><div class="col-span-1 text-center">Status</div>
            </div>
            <div id="fleet-list"><div class="p-10 text-center text-slate-400"><i class="fas fa-satellite-dish text-4xl mb-3 block"></i><p class="font-medium">Connecting to AIS data stream...</p><p class="text-xs mt-1">Vessels will appear automatically</p></div></div>
            <div class="border-t border-slate-200 px-5 py-3 flex items-center justify-between">
                <div class="text-sm text-slate-500"><span id="showing-text">Waiting for data...</span></div>
                <div class="flex items-center gap-1.5">
                    <button class="page-btn" onclick="goToPage(1)" id="btn-first"><i class="fas fa-angle-double-left text-xs"></i></button>
                    <button class="page-btn" onclick="prevPage()" id="btn-prev"><i class="fas fa-angle-left text-xs"></i></button>
                    <div id="page-numbers" class="flex items-center gap-1"></div>
                    <button class="page-btn" onclick="nextPage()" id="btn-next"><i class="fas fa-angle-right text-xs"></i></button>
                    <button class="page-btn" onclick="goToPage(totalPages)" id="btn-last"><i class="fas fa-angle-double-right text-xs"></i></button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>