<!-- resources/views/admin/dashboard.blade.php -->
<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
        <div class="p-6 lg:p-8 space-y-6 lg:space-y-8 max-w-[1600px] mx-auto">
            
            <!-- ==================== HEADER ==================== -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-lg border border-emerald-200 dark:border-emerald-500/20">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            LIVE DATA
                        </span>
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                        Nautical <span class="bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 bg-clip-text text-transparent">Command Bridge</span>
                    </h1>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch gap-4 w-full lg:w-auto">
                    <!-- Date Picker Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 flex-1 sm:flex-none sm:min-w-[200px]">
                        <label for="dashboard-date-picker" class="block text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Select Date</label>
                        <input id="dashboard-date-picker" type="date" value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" 
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 px-3 py-2.5 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <span id="dashboard-date-selected" class="font-medium">{{ now()->format('l, F j, Y') }}</span>
                        </p>
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 flex-1 sm:flex-none sm:min-w-[160px]">
                        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Total Records</p>
                        <p id="dashboard-total-count" class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPositions) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">data points available</p>
                    </div>

                    <!-- Export Button -->
                    <button id="export-dashboard-csv" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 text-white rounded-2xl text-sm font-semibold hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Snapshot
                    </button>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-2xl text-sm font-semibold hover:shadow-lg hover:shadow-red-500/25 hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- ==================== REST OF YOUR DASHBOARD CONTENT ==================== -->
            <!-- 3 CARDS IN 1 ROW -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Command Bridge Card -->
                <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-8">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-cyan-400/10 to-blue-600/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-amber-400/10 to-orange-500/5 rounded-full blur-3xl"></div>
                    
                    <div class="relative">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Command Bridge</span>
                        <h2 class="mt-2 text-xl lg:text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                            Operational intelligence for maritime control
                        </h2>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                            Monitor live traffic, track fleet performance, and make data-driven decisions.
                        </p>
                        
                        <div class="grid grid-cols-3 gap-3 mt-6">
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Types</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ count($typeStats) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Updates</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $recentVessels->total() }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uptime</p>
                                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">99.97<span class="text-sm">%</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission Health Card -->
                <div class="rounded-3xl bg-gradient-to-br from-cyan-500 via-blue-600 to-indigo-600 p-8 text-white">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold text-cyan-100 uppercase tracking-wider">Mission Health</p>
                            <h3 class="mt-1 text-xl font-bold">Fleet Readiness</h3>
                        </div>
                        <span class="px-3 py-1.5 bg-white/20 rounded-xl text-xs font-semibold uppercase tracking-wider">Stable</span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="bg-white/10 rounded-2xl p-4">
                            <p class="text-xs font-medium text-cyan-100 uppercase tracking-wider">Active Vessels</p>
                            <p class="mt-1 text-3xl font-bold">{{ $activeVessels }}</p>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-4">
                            <p class="text-xs font-medium text-cyan-100 uppercase tracking-wider">New Arrivals (1h)</p>
                            <p class="mt-1 text-3xl font-bold">{{ $newLastHour }}</p>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-4">
                            <p class="text-xs font-medium text-cyan-100 uppercase tracking-wider">Average Speed</p>
                            <p class="mt-1 text-3xl font-bold">{{ $avgSpeed }} <span class="text-lg font-medium">kn</span></p>
                        </div>
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">System Status</p>
                            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">Performance Metrics</h3>
                        </div>
                        <span class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 rounded-xl text-xs font-semibold">Optimal</span>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tracking Coverage</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">100%</span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full w-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Route Stability</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">88%</span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full w-[88%] bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Data Accuracy</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">96.5%</span>
                            </div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full w-[96.5%] bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== FLEET COMPOSITION - FULL WIDTH ==================== -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fleet Composition</p>
                        <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Vessel Type Distribution</h3>
                    </div>
                    <span class="px-4 py-2 bg-gray-50 dark:bg-gray-800 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400">{{ count($typeStats) }} Types</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($typeStats as $type)
                    <div class="group relative bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:scale-105 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br {{ $type->type === 'Container' ? 'from-purple-400/10 to-purple-600/10' : ($type->type === 'Cargo' ? 'from-blue-400/10 to-blue-600/10' : ($type->type === 'Tanker' ? 'from-amber-400/10 to-amber-600/10' : 'from-cyan-400/10 to-cyan-600/10')) }} rounded-full blur-2xl"></div>
                        
                        <div class="relative">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-3 h-3 rounded-full {{ $type->type === 'Container' ? 'bg-purple-500' : ($type->type === 'Cargo' ? 'bg-blue-500' : ($type->type === 'Tanker' ? 'bg-amber-500' : 'bg-cyan-500')) }}"></span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $type->type }}</span>
                            </div>
                            
                            <p class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $type->count }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ round(($type->count / max($totalVessels, 1)) * 100) }}% of fleet</p>
                            
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $type->type === 'Container' ? 'from-purple-500 to-purple-600' : ($type->type === 'Cargo' ? 'from-blue-500 to-blue-600' : ($type->type === 'Tanker' ? 'from-amber-500 to-amber-600' : 'from-cyan-500 to-cyan-600')) }} transition-all duration-500" 
                                     style="width: {{ ($type->count / max($totalVessels, 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- ==================== KPI CARDS ==================== -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Vessels Card -->
                <div class="group relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/5 to-cyan-500/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-cyan-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg">+{{ $newLastHour }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Vessels</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $activeVessels }}</p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">currently in transit</p>
                    </div>
                </div>

                <!-- Positions Tracked Card -->
                <div class="group relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-400/5 to-fuchsia-400/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-fuchsia-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Positions Tracked</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPositions) }}</p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">all time data points</p>
                    </div>
                </div>

                <!-- Unique Vessels Card -->
                <div class="group relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/5 to-orange-400/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unique Vessels</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalVessels }}</p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">unique MMSI tracked</p>
                    </div>
                </div>

                <!-- Average Speed Card -->
                <div class="group relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/5 to-teal-400/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fleet Avg Speed</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $avgSpeed }} <span class="text-lg font-medium text-gray-400">kn</span></p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">across all active vessels</p>
                    </div>
                </div>
            </div>

            <!-- ==================== LIVE VESSEL FEED TABLE ==================== -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Live Vessel Feed</h3>
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">LIVE</span>
                        </div>
                    </div>
                    <select id="per-page-select" onchange="changePerPage(this.value)" 
                        class="px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                    </select>
                </div>
                
                <div class="overflow-x-auto">
                    <table id="recent-vessels-table" class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">#</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vessel</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">MMSI</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="text-center py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Speed</th>
                                <th class="text-left py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Destination</th>
                                <th class="text-right py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Last Seen</th>
                            <tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($recentVessels as $index => $vessel)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="py-3 text-sm text-gray-400 font-mono">{{ $recentVessels->firstItem() + $index }}</td>
                                <td class="py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ strtoupper(substr($vessel->name, 0, 2)) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $vessel->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <code class="text-xs bg-gray-50 dark:bg-gray-800 px-2 py-1 rounded-md text-gray-600 dark:text-gray-400">{{ $vessel->mmsi }}</code>
                                </td>
                                <td class="py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg
                                        {{ $vessel->type === 'Container' ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400' : '' }}
                                        {{ $vessel->type === 'Cargo' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : '' }}
                                        {{ $vessel->type === 'Tanker' ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' : '' }}
                                        {{ !in_array($vessel->type, ['Container','Cargo','Tanker']) ? 'bg-gray-50 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400' : '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $vessel->type === 'Container' ? 'bg-purple-500' : ($vessel->type === 'Cargo' ? 'bg-blue-500' : ($vessel->type === 'Tanker' ? 'bg-amber-500' : 'bg-gray-500')) }}"></span>
                                        {{ $vessel->type }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $vessel->latestPosition?->speed ?? 0 }} <span class="text-xs text-gray-400 font-normal">kn</span></span>
                                </td>
                                <td class="py-3">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 max-w-[140px] truncate block">{{ $vessel->latestPosition?->destination ?? '—' }}</span>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $vessel->latestPosition?->received_at?->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <span class="text-sm text-gray-400">No vessel data available</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                @if($recentVessels->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $recentVessels->firstItem() }}</span> to <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $recentVessels->lastItem() }}</span> of <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $recentVessels->total() }}</span> vessels
                    </div>
                    <div class="flex items-center gap-1">
                        {{-- Previous --}}
                        @if($recentVessels->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $recentVessels->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($recentVessels->getUrlRange(max(1, $recentVessels->currentPage() - 2), min($recentVessels->lastPage(), $recentVessels->currentPage() + 2)) as $page => $url)
                            @if($page == $recentVessels->currentPage())
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-semibold shadow-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition-all">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($recentVessels->hasMorePages())
                            <a href="{{ $recentVessels->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function changePerPage(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', value);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }

            document.addEventListener('DOMContentLoaded', function () {
                const exportButton = document.getElementById('export-dashboard-csv');
                const table = document.getElementById('recent-vessels-table');
                const datePicker = document.getElementById('dashboard-date-picker');
                const selectedDateDisplay = document.getElementById('dashboard-date-selected');

                if (datePicker && selectedDateDisplay) {
                    datePicker.addEventListener('change', function () {
                        const chosen = datePicker.value ? new Date(datePicker.value) : new Date();
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        selectedDateDisplay.textContent = chosen.toLocaleDateString(undefined, options);
                    });
                }

                if (!exportButton || !table) return;

                exportButton.addEventListener('click', function () {
                    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
                    const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr => {
                        return Array.from(tr.querySelectorAll('td')).map(td => td.textContent.trim().replace(/\s+/g, ' '));
                    });
                    if (rows.length === 0) {
                        alert('No vessel data available to export.');
                        return;
                    }
                    const csvContent = [headers.join(','), ...rows.map(r => r.map(cell => '"' + cell.replace(/"/g, '""') + '"').join(','))].join('\n');
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', 'dashboard-vessels-' + (datePicker?.value || new Date().toISOString().slice(0, 10)) + '.csv');
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                });
            });
        </script>
    @endpush
</x-layouts.app>