<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarineTrack – Global Maritime Intelligence</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
   @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="text-white">
    <div id="map"></div>

    <!-- ==================== TOP BAR ==================== -->
    <div class="fixed top-3 left-1/2 -translate-x-1/2 z-[1000] glass rounded-2xl px-5 py-2.5 flex items-center gap-5 text-sm">
        <div class="flex items-center gap-2.5">
            
            <div class="back-link px-3 py-1.5 bg-white/10 text-white rounded-lg text-[10px] font-semibold hover:bg-white/20 transition">
                <a href='{{ route('home') }}'>Back</a>
            </div>
            <div>
                <h1 class="text-sm font-extrabold gradient-text">MarineTrack</h1>
                <p class="text-[8px] text-slate-500 tracking-wider uppercase">Maritime Intelligence</p>
            </div>
        </div>
        <div class="w-px h-7 bg-white/10"></div>
        <div class="text-center"><p class="text-[8px] text-slate-500 uppercase">Vessels</p><p id="ship-count" class="text-lg font-bold stat-val">0</p></div>
        <div class="w-px h-7 bg-white/10"></div>
        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span></span>
        <span id="status-text" class="text-emerald-400 font-semibold text-xs">LIVE</span>
        <div class="w-px h-7 bg-white/10"></div>
        <button onclick="clearAll()" class="px-3 py-1.5 bg-red-500/10 text-red-400 rounded-lg text-[10px] font-semibold hover:bg-red-500/20 transition"><i class="fas fa-trash-alt mr-1"></i>Clear</button>
    </div>

    <!-- ==================== FILTER SIDEBAR (LEFT) ==================== -->
    <div class="fixed left-3 top-20 z-[999] glass rounded-2xl w-60 flex flex-col overflow-hidden max-h-[calc(100vh-100px)]">
        <div class="p-3 border-b border-white/5 flex-shrink-0">
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"><i class="fas fa-sliders-h mr-1"></i>Filters</h3>
        </div>
        <div class="flex-1 overflow-y-auto scroll-thin p-3 space-y-4">
            <div>
                <p class="text-[9px] font-bold text-slate-500 uppercase mb-2">Vessel Types</p>
                <div class="space-y-1">
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Container"> <span class="type-dot" style="background:var(--container)"></span> Container</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Cargo"> <span class="type-dot" style="background:var(--cargo)"></span> Cargo</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Tanker"> <span class="type-dot" style="background:var(--tanker)"></span> Tanker</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Cruise"> <span class="type-dot" style="background:var(--cruise)"></span> Cruise</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Bulk"> <span class="type-dot" style="background:var(--bulk)"></span> Bulk</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Tug"> <span class="type-dot" style="background:var(--tug)"></span> Tug</label>
                    <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="applyFilters()" data-filter="Fishing"> <span class="type-dot" style="background:var(--fishing)"></span> Fishing</label>
                </div>
            </div>
            <div class="border-t border-white/5 pt-3">
                <p class="text-[9px] font-bold text-slate-500 uppercase mb-2">Infrastructure</p>
                <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="togglePorts()" id="show-ports"> <span class="type-dot" style="background:var(--port)"></span> Ports</label>
                <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="toggleLighthouses()" id="show-lighthouses"> <span class="type-dot" style="background:var(--lighthouse)"></span> Lighthouses</label>
            </div>
            <div class="border-t border-white/5 pt-3">
                <p class="text-[9px] font-bold text-slate-500 uppercase mb-2">Options</p>
                <label class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-white/3 cursor-pointer text-[11px]"><input type="checkbox" checked onchange="filterBySpeed=!filterBySpeed;applyFilters()" id="speed-filter"> <span class="type-dot" style="background:#22c55e"></span> Moving Only</label>
            </div>
            <div class="border-t border-white/5 pt-3">
                <p class="text-[9px] font-bold text-slate-500 uppercase mb-2">Fleet Stats</p>
                <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                    <div class="bg-white/3 rounded-lg p-2 text-center"><p class="text-slate-500">Container</p><p id="fs-container" class="font-bold text-purple-400 stat-val">0</p></div>
                    <div class="bg-white/3 rounded-lg p-2 text-center"><p class="text-slate-500">Cargo</p><p id="fs-cargo" class="font-bold text-blue-400 stat-val">0</p></div>
                    <div class="bg-white/3 rounded-lg p-2 text-center"><p class="text-slate-500">Tanker</p><p id="fs-tanker" class="font-bold text-amber-400 stat-val">0</p></div>
                    <div class="bg-white/3 rounded-lg p-2 text-center"><p class="text-slate-500">Other</p><p id="fs-other" class="font-bold text-cyan-400 stat-val">0</p></div>
                </div>
            </div>
        </div>
        <div class="p-2 border-t border-white/5 flex-shrink-0">
            <button onclick="resetFilters()" class="w-full py-1.5 bg-blue-500/10 text-blue-400 rounded-lg text-[10px] font-bold hover:bg-blue-500/20 transition"><i class="fas fa-redo-alt mr-1"></i>Reset All</button>
        </div>
    </div>

    <!-- ==================== SHIP LIST (BOTTOM LEFT) ==================== -->
    <div class="fixed left-64 bottom-4 z-[999] glass rounded-2xl w-72 flex flex-col overflow-hidden max-h-[45vh]">
        <div class="p-2.5 border-b border-white/5 flex-shrink-0 flex items-center gap-2">
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"><i class="fas fa-list-ul mr-1"></i>Active Fleet</h3>
            <span id="list-count" class="text-[10px] text-slate-500 ml-auto">0</span>
        </div>
        <div class="relative flex-shrink-0 px-2 pb-2">
            <input type="text" id="ship-search" oninput="renderShipList()" placeholder="Search vessel..." class="w-full bg-white/5 border border-white/10 rounded-lg pl-8 pr-2 py-1.5 text-[11px] text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500/50">
            <i class="fas fa-search absolute left-4 top-2 text-slate-500 text-[10px]"></i>
        </div>
        <div id="ship-list-container" class="flex-1 overflow-y-auto scroll-thin"><div class="p-4 text-center text-slate-500 text-[11px]"><i class="fas fa-spinner fa-pulse mr-2"></i>Waiting for vessels...</div></div>
    </div>

    <!-- ==================== VESSEL DETAILS (RIGHT) ==================== -->
    <div id="vessel-details" class="fixed right-3 top-20 z-[999] glass rounded-2xl p-4 w-64 max-h-[calc(100vh-100px)] overflow-y-auto scroll-thin hidden">
        <button onclick="document.getElementById('vessel-details').classList.add('hidden')" class="absolute top-2 right-2 text-slate-500 hover:text-white text-base"><i class="fas fa-times-circle"></i></button>
        <h3 class="text-xs font-bold gradient-text mb-3"><i class="fas fa-info-circle mr-1"></i><span id="detail-name">Vessel Details</span></h3>
        <div class="space-y-2 text-[11px]">
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">MMSI</span><br><span id="detail-mmsi" class="font-mono font-bold">-</span></div>
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">Type</span><br><span id="detail-type" class="text-cyan-400 font-bold">-</span></div>
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">Speed</span><br><span id="detail-speed" class="font-bold">0 kn</span></div>
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">Course</span><br><span id="detail-course" class="font-bold">0°</span></div>
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">Destination</span><br><span id="detail-destination" class="text-amber-400 font-bold">-</span></div>
            <div class="bg-white/3 rounded-lg p-2"><span class="text-slate-500">Distance</span><br><span id="detail-distance" class="font-bold">0 nm</span></div>
        </div>
    </div>

    <!-- ==================== BOTTOM INFO BAR ==================== -->
    <div id="info-bar" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-[1000] glass rounded-2xl px-4 py-2.5 hidden">
        <div class="flex items-center gap-4 text-[11px]">
            <span id="bar-name" class="font-bold text-cyan-400"></span>
            <span class="text-slate-600">|</span>
            <span><i class="fas fa-tachometer-alt mr-1"></i><span id="bar-speed" class="font-bold"></span></span>
            <span class="text-slate-600">|</span>
            <span><i class="fas fa-compass mr-1"></i><span id="bar-course" class="font-bold"></span></span>
            <span class="text-slate-600">|</span>
            <span><i class="fas fa-flag-checkered mr-1"></i><span id="bar-dest" class="font-bold text-amber-400"></span></span>
            <span class="text-slate-600">|</span>
            <span><i class="fas fa-ruler-combined mr-1"></i><span id="bar-dist" class="font-bold text-emerald-400"></span></span>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
</body>
</html>