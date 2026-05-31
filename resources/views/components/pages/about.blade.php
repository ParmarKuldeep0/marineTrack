<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About – MarineTrack Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg: #f8fafc;
            --text: #1e293b;
            --text-secondary: #475569;
            --accent: #0284c7;
            --accent2: #7c3aed;
            --card: white;
            --border: #e2e8f0;
        }
        
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { 
            margin: 0; padding: 0; 
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 30%, #f5f3ff 70%, #f8fafc 100%);
            min-height: 100vh;
        }
        
        .gradient-text { 
            background: linear-gradient(135deg, #0284c7, #7c3aed); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        
        .hero-glow {
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.12;
            pointer-events: none;
        }
        
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.08);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0284c7, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .timeline-dot {
            width: 12px; height: 12px;
            background: linear-gradient(135deg, #0284c7, #7c3aed);
            border-radius: 50%;
            position: absolute;
            left: -6px;
            top: 6px;
            box-shadow: 0 0 12px rgba(124,58,237,0.3);
        }
    </style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@include('components.pages.header')   
</head>

<body class="text-slate-700">
    <!-- ==================== HERO SECTION ==================== -->
    <div class="relative overflow-hidden">
        <div class="hero-glow bg-cyan-400" style="top:-200px;left:-100px;"></div>
        <div class="hero-glow bg-purple-400" style="bottom:-200px;right:-100px;"></div>
        
        <div class="max-w-5xl mx-auto px-6 pt-20 pb-16 text-center relative z-10">
            <span class="inline-flex items-center gap-2 bg-white/80 border border-slate-200 px-4 py-1.5 rounded-full text-sm font-semibold text-slate-500 mb-6 shadow-sm">
                <i class="fas fa-ship text-cyan-500"></i> Maritime Intelligence Platform
            </span>
            
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                About <span class="gradient-text">MarineTrack Pro</span>
            </h1>
            
            <p class="text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Real-time global vessel tracking powered by live AIS data. Monitor container ships, tankers, cruise liners, fishing vessels, and maritime infrastructure worldwide.
            </p>
        </div>
    </div>

    <!-- ==================== STATS ROW ==================== -->
    <div class="max-w-5xl mx-auto px-6 -mt-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-6 text-center">
                <i class="fas fa-ship text-3xl text-cyan-500 mb-3"></i>
                <p class="stat-number">500+</p>
                <p class="text-sm text-slate-500 font-medium">Active Vessels Tracked</p>
            </div>
            <div class="card p-6 text-center">
                <i class="fas fa-anchor text-3xl text-amber-500 mb-3"></i>
                <p class="stat-number">12</p>
                <p class="text-sm text-slate-500 font-medium">Major Ports Monitored</p>
            </div>
            <div class="card p-6 text-center">
                <i class="fas fa-lighthouse text-3xl text-yellow-500 mb-3"></i>
                <p class="stat-number">6</p>
                <p class="text-sm text-slate-500 font-medium">Lighthouses</p>
            </div>
            <div class="card p-6 text-center">
                <i class="fas fa-globe text-3xl text-emerald-500 mb-3"></i>
                <p class="stat-number">24/7</p>
                <p class="text-sm text-slate-500 font-medium">Real-Time Tracking</p>
            </div>
        </div>
    </div>

    <!-- ==================== VESSEL TYPES SECTION ==================== -->
    <div class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-center mb-4 gradient-text">Vessel Types We Track</h2>
        <p class="text-center text-slate-500 mb-10">Comprehensive maritime intelligence across all vessel categories</p>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(124,58,237,0.1)">
                    <i class="fas fa-box text-xl text-purple-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Container Ships</h3>
                <p class="text-xs text-slate-500">MSC, Maersk, CMA CGM, COSCO, HMM, ONE, Evergreen</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(37,99,235,0.1)">
                    <i class="fas fa-ship text-xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Cargo Vessels</h3>
                <p class="text-xs text-slate-500">Bulk carriers, General cargo, Heavy lift, Timber carriers</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(217,119,6,0.1)">
                    <i class="fas fa-oil-can text-xl text-amber-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Tankers</h3>
                <p class="text-xs text-slate-500">Oil, LNG, LPG, Chemical, Crude carriers</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(219,39,119,0.1)">
                    <i class="fas fa-umbrella-beach text-xl text-pink-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Cruise Ships</h3>
                <p class="text-xs text-slate-500">Carnival, Royal Caribbean, Norwegian, Disney Cruise</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(5,150,105,0.1)">
                    <i class="fas fa-cubes text-xl text-emerald-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Bulk Carriers</h3>
                <p class="text-xs text-slate-500">Ore, Coal, Grain, Cement, Fertilizer</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(13,148,136,0.1)">
                    <i class="fas fa-life-ring text-xl text-teal-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Tugs & Support</h3>
                <p class="text-xs text-slate-500">Svitzer, Multratug, Smit, Damen, Boluda</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(8,145,178,0.1)">
                    <i class="fas fa-fish text-xl text-cyan-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Fishing Vessels</h3>
                <p class="text-xs text-slate-500">Trawlers, Seiners, Longliners, Crabbers</p>
            </div>
            <div class="card p-5 text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3" style="background:rgba(202,138,4,0.1)">
                    <i class="fas fa-anchor text-xl text-yellow-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Ports & Infrastructure</h3>
                <p class="text-xs text-slate-500">Ports, Lighthouses, Beacons, Navigation aids</p>
            </div>
        </div>
    </div>

    <!-- ==================== FEATURES SECTION ==================== -->
    <div class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-center mb-4 gradient-text">Platform Features</h2>
        <p class="text-center text-slate-500 mb-10">Everything you need for comprehensive maritime intelligence</p>
        
        <div class="grid md:grid-cols-3 gap-6">
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(2,132,199,0.1)">
                    <i class="fas fa-satellite-dish text-lg text-cyan-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Real-Time AIS Data</h3>
                <p class="text-sm text-slate-500">Live vessel positions updated every few seconds from AIS transponders worldwide.</p>
            </div>
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(124,58,237,0.1)">
                    <i class="fas fa-filter text-lg text-purple-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Advanced Filtering</h3>
                <p class="text-sm text-slate-500">Filter by vessel type, speed, destination. Show only moving vessels or specific categories.</p>
            </div>
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(5,150,105,0.1)">
                    <i class="fas fa-route text-lg text-emerald-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Route Prediction</h3>
                <p class="text-sm text-slate-500">Smart destination prediction based on vessel heading and known shipping lanes.</p>
            </div>
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(217,119,6,0.1)">
                    <i class="fas fa-search text-lg text-amber-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Vessel Search</h3>
                <p class="text-sm text-slate-500">Search by vessel name, MMSI number, destination, or type. Find any ship instantly.</p>
            </div>
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(219,39,119,0.1)">
                    <i class="fas fa-chart-bar text-lg text-pink-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Fleet Statistics</h3>
                <p class="text-sm text-slate-500">Real-time fleet stats: container count, cargo vessels, tankers, average speed, and more.</p>
            </div>
            <div class="card p-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-4" style="background:rgba(8,145,178,0.1)">
                    <i class="fas fa-map-marked-alt text-lg text-cyan-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Infrastructure Map</h3>
                <p class="text-sm text-slate-500">Ports, lighthouses, and navigation aids displayed on an interactive dark-themed map.</p>
            </div>
        </div>
    </div>

    <!-- ==================== HOW IT WORKS ==================== -->
    <div class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-center mb-4 gradient-text">How It Works</h2>
        <p class="text-center text-slate-500 mb-10">From AIS signal to your screen in milliseconds</p>
        
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(2,132,199,0.1)">
                    <i class="fas fa-broadcast-tower text-2xl text-cyan-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">1. AIS Broadcast</h3>
                <p class="text-xs text-slate-500">Ships broadcast position, speed, course, and destination via AIS transponders.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(124,58,237,0.1)">
                    <i class="fas fa-satellite text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">2. Satellite/Receiver</h3>
                <p class="text-xs text-slate-500">Ground stations and satellites receive AIS signals from vessels worldwide.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(5,150,105,0.1)">
                    <i class="fas fa-server text-2xl text-emerald-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">3. Data Processing</h3>
                <p class="text-xs text-slate-500">AISstream processes and streams the data via WebSocket in real-time.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(217,119,6,0.1)">
                    <i class="fas fa-desktop text-2xl text-amber-600"></i>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">4. Your Screen</h3>
                <p class="text-xs text-slate-500">Vessels appear on your map with type detection, routes, and filters.</p>
            </div>
        </div>
    </div>

    <!-- ==================== TECH STACK ==================== -->
    <div class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-center mb-4 gradient-text">Technology Stack</h2>
        <p class="text-center text-slate-500 mb-10">Built with modern, fast, and reliable technologies</p>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-code text-2xl text-cyan-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">Vanilla JavaScript</h3>
                    <p class="text-xs text-slate-500">No frameworks, no dependencies. Pure, fast JavaScript for maximum performance.</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-map text-2xl text-emerald-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">Leaflet.js</h3>
                    <p class="text-xs text-slate-500">Lightweight, open-source mapping library with Canvas renderer for smooth performance.</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-network-wired text-2xl text-purple-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">WebSocket Streaming</h3>
                    <p class="text-xs text-slate-500">Real-time AIS data via WebSocket connection to AISstream.io.</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-paint-brush text-2xl text-pink-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">Tailwind CSS</h3>
                    <p class="text-xs text-slate-500">Utility-first CSS framework for a clean, responsive, professional UI.</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-icons text-2xl text-amber-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">Font Awesome 6</h3>
                    <p class="text-xs text-slate-500">Professional vector icons for a polished, modern interface.</p>
                </div>
            </div>
            <div class="card p-5 flex items-center gap-4">
                <i class="fas fa-database text-2xl text-blue-500 w-8 text-center"></i>
                <div>
                    <h3 class="font-bold text-slate-800">AISstream API</h3>
                    <p class="text-xs text-slate-500">Free AIS data provider with worldwide vessel position coverage.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== FOOTER ==================== -->
    <div class="border-t border-slate-200 bg-white/50">
        <div class="max-w-5xl mx-auto px-6 py-8 text-center">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ship text-white text-xs"></i>
                </div>
                <h3 class="font-bold gradient-text text-lg">MarineTrack Pro</h3>
            </div>
            <p class="text-sm text-slate-500 mb-4">Global Maritime Intelligence Platform</p>
            <p class="text-xs text-slate-400">
                Data sourced from <a href="https://aisstream.io" class="text-cyan-600 hover:underline" target="_blank">AISstream.io</a> · 
                Map tiles by <a href="https://carto.com" class="text-cyan-600 hover:underline" target="_blank">CartoDB</a> · 
                Icons by <a href="https://fontawesome.com" class="text-cyan-600 hover:underline" target="_blank">Font Awesome</a>
            </p>
            <p class="text-xs text-slate-400 mt-2">© 2024 MarineTrack Pro. All rights reserved.</p>
        </div>
    </div>

</body>
</html>