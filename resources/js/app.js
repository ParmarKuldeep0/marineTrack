// ==========================================
// 🔑 CONFIGURATION
// ==========================================
let KEY = null;
const MAX_SHIPS = 1000;
const MAX_BACKEND_CONCURRENCY = 4;
const backendQueue = [];
let backendActive = 0;

async function getKey() {
    try {
        const response = await fetch("/ais-token");
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        KEY = data.key;
        console.log("✅ API Key loaded");
        return KEY;
    } catch (error) {
        console.error("❌ Failed to load API key:", error);
        return null;
    }
}

// ==========================================
// 🌍 PORTS - Loaded dynamically from database
// ==========================================
let PORTS = {};

async function loadPortsFromDatabase() {
    try {
        const response = await fetch("/api/ports");
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        
        if (data.success && data.ports && Array.isArray(data.ports)) {
            const portsObject = {};
            data.ports.forEach(port => {
                if (port.latitude && port.longitude) {
                    portsObject[port.name.toUpperCase()] = [parseFloat(port.latitude), parseFloat(port.longitude)];
                }
            });
            PORTS = portsObject;
            console.log(`📌 Loaded ${Object.keys(PORTS).length} ports from database`);
        } else {
            // Fallback ports
            PORTS = {
                'ROTTERDAM': [51.9225, 4.4792],
                'ANTWERP': [51.2211, 4.3997],
                'HAMBURG': [53.5488, 9.9872],
                'LONDON': [51.5074, 0.1278],
                'SINGAPORE': [1.3521, 103.8198],
                'SHANGHAI': [31.2304, 121.4737],
            };
            console.log("📌 Using fallback ports");
        }
    } catch (err) {
        console.error("Failed to load ports:", err);
        PORTS = {
            'ROTTERDAM': [51.9225, 4.4792],
            'ANTWERP': [51.2211, 4.3997],
            'HAMBURG': [53.5488, 9.9872],
        };
    }
}

// ==========================================
// 🚢 VESSEL TYPES
// ==========================================
const VESSEL_TYPES = {
    Container: { color: "#7c3aed", icon: "fa-box" },
    Cargo: { color: "#2563eb", icon: "fa-ship" },
    Tanker: { color: "#d97706", icon: "fa-oil-can" },
    Cruise: { color: "#db2777", icon: "fa-umbrella-beach" },
    Bulk: { color: "#059669", icon: "fa-cubes" },
    Tug: { color: "#0d9488", icon: "fa-life-ring" },
    Fishing: { color: "#0891b2", icon: "fa-fish" },
};

// ==========================================
// 🧭 UTILITY FUNCTIONS
// ==========================================
function getDist(a, b, c, d) {
    const R = 3440;
    const x = ((c - a) * Math.PI) / 180;
    const y = ((d - b) * Math.PI) / 180;
    const s = Math.sin(x / 2) ** 2 + Math.cos((a * Math.PI) / 180) * Math.cos((c * Math.PI) / 180) * Math.sin(y / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(s), Math.sqrt(1 - s));
}

function getBearing(a, b, c, d) {
    const y = Math.sin(((d - b) * Math.PI) / 180) * Math.cos((c * Math.PI) / 180);
    const x = Math.cos((a * Math.PI) / 180) * Math.sin((c * Math.PI) / 180) - Math.sin((a * Math.PI) / 180) * Math.cos((c * Math.PI) / 180) * Math.cos(((d - b) * Math.PI) / 180);
    return ((Math.atan2(y, x) * 180) / Math.PI + 360) % 360;
}

function getVesselType(mmsi) {
    const t = Object.keys(VESSEL_TYPES);
    return t[Math.abs(parseInt(mmsi) || 0) % t.length];
}

function predictDest(lat, lng, heading) {
    if (Object.keys(PORTS).length === 0) return null;
    let best = null, bs = -Infinity;
    for (const [n, c] of Object.entries(PORTS)) {
        if (!c || c.length < 2) continue;
        const d = getDist(lat, lng, c[0], c[1]);
        const b = getBearing(lat, lng, c[0], c[1]);
        const bd = Math.min(Math.abs(heading - b), 360 - Math.abs(heading - b));
        const s = (bd < 60 ? (60 - bd) / 60 : 0) * 0.7 + Math.max(0, 1 - d / 4000) * 0.3;
        if (s > bs) {
            bs = s;
            best = { name: n, coords: c, dist: d };
        }
    }
    return best && bs > 0.15 ? best : null;
}

// ==========================================
// 📤 SAVE TO DATABASE
// ==========================================
function processBackendQueue() {
    while (backendActive < MAX_BACKEND_CONCURRENCY && backendQueue.length > 0) {
        const payload = backendQueue.shift();
        backendActive++;
        
        fetch("/api/positions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
            },
            body: JSON.stringify(payload),
        })
            .catch(err => console.error("Save error:", err))
            .finally(() => {
                backendActive--;
                processBackendQueue();
            });
    }
}

function sendToBackend(vesselData) {
    const payload = {
        mmsi: vesselData.mmsi,
        name: vesselData.name || null,
        latitude: parseFloat(vesselData.lat || 0),
        longitude: parseFloat(vesselData.lng || 0),
        speed: parseFloat(vesselData.speed || 0),
        course: parseFloat(vesselData.heading || vesselData.course || 0),
        heading: parseFloat(vesselData.heading || 0),
        destination: vesselData.dest || null,
    };
    
    if (!payload.mmsi || payload.latitude === 0 || payload.longitude === 0) return;
    
    backendQueue.push(payload);
    processBackendQueue();
}

// ==========================================
// 🚀 AUTO-DETECT PAGE TYPE
// ==========================================
const isMapPage = document.getElementById("map") !== null;
const isFleetPage = document.getElementById("fleet-list") !== null;

console.log("Page detected:", isMapPage ? "Tracking Map" : isFleetPage ? "Fleet Management" : "Unknown");

// ==========================================
// 🗺️ MAP PAGE LOGIC
// ==========================================
if (isMapPage) {
    const map = L.map("map", {
        center: [51, 2],
        zoom: 7,
        preferCanvas: true,
        attributionControl: false,
    });
    L.tileLayer("https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png", { maxZoom: 19 }).addTo(map);

    const ships = new Map();
    const markers = new Map();
    const portMarkers = new Map();
    const lighthouseMarkers = new Map();
    let routeLine = null, routeDest = null, selected = null;
    let filterBySpeed = true;
    let isFull = false;
    let messageCount = 0;

    function createIcon(speed, heading, type) {
        const s = 12;
        let c = VESSEL_TYPES[type]?.color || "#64748b";
        const spd = parseFloat(speed) || 0;
        if (spd > 15) c = "#10b981";
        else if (spd > 5) c = "#06b6d4";
        else if (spd > 0.5) c = "#f59e0b";
        const hdg = parseFloat(heading) || 0;
        return L.divIcon({
            html: `<div style="width:${s}px;height:${s}px;transform:rotate(${hdg}deg);"><svg viewBox="0 0 24 24" width="${s}" height="${s}"><path d="M12 2L3 20h18L12 2z" fill="${c}" stroke="white" stroke-width="0.5"/></svg></div>`,
            className: "",
            iconSize: [s, s],
            iconAnchor: [s / 2, s / 2],
        });
    }

    function clearAll() {
        if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
        if (routeDest) { map.removeLayer(routeDest); routeDest = null; }
        markers.forEach(m => map.removeLayer(m));
        markers.clear();
        ships.clear();
        selected = null;
        isFull = false;
        document.getElementById("info-bar")?.classList.add("hidden");
        document.getElementById("vessel-details")?.classList.add("hidden");
        document.getElementById("ship-count").textContent = "0";
        document.getElementById("list-count").textContent = "0";
        document.getElementById("status-text").textContent = "LIVE";
        document.getElementById("status-text").className = "text-emerald-400 font-semibold text-xs";
        renderShipList();
        updateFilterStats();
    }

    function showRoute(mmsi) {
        if (routeLine) { map.removeLayer(routeLine); routeLine = null; }
        if (routeDest) { map.removeLayer(routeDest); routeDest = null; }
        const s = ships.get(mmsi);
        if (!s) return;
        
        let dc = null, dn = "Unknown";
        const speed = parseFloat(s.speed) || 0;
        const heading = parseFloat(s.heading) || 0;
        
        // Try to match destination from declared port
        if (s.dest) {
            const destKey = s.dest.toUpperCase().trim();
            if (PORTS[destKey]) {
                dc = PORTS[destKey];
                dn = s.dest;
            } else {
                for (const [portName, coords] of Object.entries(PORTS)) {
                    if (destKey.includes(portName) || portName.includes(destKey)) {
                        dc = coords;
                        dn = portName;
                        break;
                    }
                }
            }
        }
        
        // If no destination, predict based on heading
        if (!dc && speed > 0.3) {
            const predicted = predictDest(parseFloat(s.lat), parseFloat(s.lng), heading);
            if (predicted) {
                dc = predicted.coords;
                dn = `${predicted.name} (predicted)`;
            }
        }
        
        // Update UI
        document.getElementById("detail-name").textContent = s.name;
        document.getElementById("detail-mmsi").textContent = s.mmsi;
        document.getElementById("detail-type").textContent = s.type;
        document.getElementById("detail-speed").textContent = speed.toFixed(1) + " kn";
        document.getElementById("detail-course").textContent = heading.toFixed(0) + "°";
        document.getElementById("detail-destination").textContent = s.dest || dn;
        
        if (dc && dc.length === 2) {
            const dist = getDist(parseFloat(s.lat), parseFloat(s.lng), parseFloat(dc[0]), parseFloat(dc[1]));
            
            routeLine = L.polyline([[parseFloat(s.lat), parseFloat(s.lng)], [parseFloat(dc[0]), parseFloat(dc[1])]], {
                color: s.dest ? "#00d4ff" : "#f59e0b",
                weight: 3,
                opacity: 0.8,
                dashArray: s.dest ? "12 8" : "6 6",
            }).addTo(map);
            
            routeDest = L.marker([parseFloat(dc[0]), parseFloat(dc[1])], {
                icon: L.divIcon({
                    html: `<div style="width:10px;height:10px;background:${s.dest ? "#ef4444" : "#f59e0b"};border:2px solid white;border-radius:50%;box-shadow:0 0 8px ${s.dest ? "#ef4444" : "#f59e0b"};"></div>`,
                    className: "",
                    iconSize: [10, 10],
                    iconAnchor: [5, 5],
                }),
            }).addTo(map);
            
            document.getElementById("detail-distance").textContent = Math.round(dist) + " nm";
            document.getElementById("bar-dist").textContent = Math.round(dist) + " nm";
        } else {
            document.getElementById("detail-distance").textContent = "N/A";
            document.getElementById("bar-dist").textContent = "N/A";
        }
        
        document.getElementById("bar-name").textContent = s.name;
        document.getElementById("bar-speed").textContent = speed.toFixed(1) + " kn";
        document.getElementById("bar-course").textContent = heading.toFixed(0) + "°";
        document.getElementById("bar-dest").textContent = s.dest || dn;
        document.getElementById("info-bar").classList.remove("hidden");
        document.getElementById("vessel-details").classList.remove("hidden");
        
        renderShipList();
    }

    function renderShipList() {
        const el = document.getElementById("ship-list-container");
        if (!el) return;
        const q = (document.getElementById("ship-search")?.value || "").toLowerCase();
        let list = [...ships.values()];
        if (q) {
            list = list.filter(s =>
                s.name.toLowerCase().includes(q) ||
                s.mmsi.includes(q) ||
                (s.dest || "").toLowerCase().includes(q) ||
                s.type.toLowerCase().includes(q)
            );
        }
        list.sort((a, b) => (parseFloat(b.speed) || 0) - (parseFloat(a.speed) || 0));
        document.getElementById("list-count").textContent = ships.size + " vessels";
        
        if (list.length === 0) {
            el.innerHTML = '<div class="p-4 text-center text-slate-500 text-[11px]">No vessels found</div>';
            return;
        }
        
        el.innerHTML = list.slice(0, 60).map(s => {
            const tc = VESSEL_TYPES[s.type]?.color || "#64748b";
            const speed = parseFloat(s.speed) || 0;
            return `<div class="ship-row flex items-center justify-between ${s.mmsi === selected ? "active" : ""}" data-mmsi="${s.mmsi}">
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] font-semibold truncate"><span class="type-dot" style="background:${tc}"></span> ${s.name}</p>
                    <p class="text-[9px] text-slate-500">${s.type} · ${speed.toFixed(1)}kn${s.dest ? " · " + s.dest : ""}</p>
                </div>
            </div>`;
        }).join("");
        
        el.querySelectorAll(".ship-row").forEach(row => {
            row.addEventListener("click", () => {
                const mmsi = row.dataset.mmsi;
                const vessel = ships.get(mmsi);
                if (vessel) {
                    selected = mmsi;
                    map.flyTo([parseFloat(vessel.lat), parseFloat(vessel.lng)], 9, { duration: 1 });
                    showRoute(mmsi);
                }
            });
        });
    }

    function isTypeVisible(type) {
        const cbs = document.querySelectorAll("[data-filter]");
        for (const cb of cbs) {
            if (cb.dataset.filter === type) return cb.checked;
        }
        return true;
    }

    function applyFilters() {
        markers.forEach((mk, mmsi) => {
            const s = ships.get(mmsi);
            if (!s) return;
            const speed = parseFloat(s.speed) || 0;
            const show = isTypeVisible(s.type) && (!filterBySpeed || speed > 0.5);
            if (show && !map.hasLayer(mk)) map.addLayer(mk);
            else if (!show && map.hasLayer(mk)) map.removeLayer(mk);
        });
        updateFilterStats();
    }

    function updateFilterStats() {
        let cc = 0, ca = 0, ta = 0, ot = 0;
        ships.forEach(s => {
            const speed = parseFloat(s.speed) || 0;
            if (isTypeVisible(s.type) && (!filterBySpeed || speed > 0.5)) {
                if (s.type === "Container") cc++;
                else if (s.type === "Cargo") ca++;
                else if (s.type === "Tanker") ta++;
                else ot++;
            }
        });
        document.getElementById("fs-container").textContent = cc;
        document.getElementById("fs-cargo").textContent = ca;
        document.getElementById("fs-tanker").textContent = ta;
        document.getElementById("fs-other").textContent = ot;
    }

    function resetFilters() {
        document.querySelectorAll("[data-filter]").forEach(cb => cb.checked = true);
        document.getElementById("speed-filter").checked = true;
        filterBySpeed = true;
        document.getElementById("show-ports").checked = true;
        document.getElementById("show-lighthouses").checked = true;
        togglePorts();
        toggleLighthouses();
        applyFilters();
    }

    function togglePorts() {
        const show = document.getElementById("show-ports").checked;
        portMarkers.forEach(mk => show ? map.addLayer(mk) : map.removeLayer(mk));
    }

    function toggleLighthouses() {
        const show = document.getElementById("show-lighthouses").checked;
        lighthouseMarkers.forEach(mk => show ? map.addLayer(mk) : map.removeLayer(mk));
    }

    function addPortMarkers() {
        portMarkers.forEach(mk => map.removeLayer(mk));
        portMarkers.clear();
        Object.entries(PORTS).forEach(([name, coords]) => {
            if (!coords || coords.length < 2) return;
            const mk = L.marker([parseFloat(coords[0]), parseFloat(coords[1])], {
                icon: L.divIcon({
                    html: `<div class="w-4 h-4 bg-amber-500 rounded-full border-2 border-amber-300 shadow-lg"></div>`,
                    className: "",
                    iconSize: [16, 16],
                    iconAnchor: [8, 8],
                }),
            }).addTo(map);
            mk.bindTooltip(name, { permanent: true, direction: "right", className: "port-label" });
            portMarkers.set(name, mk);
        });
        togglePorts();
    }

    // Lighthouses
    const lighthouses = [
        { n: "Fastnet", c: [51.39, -9.6] },
        { n: "Eddystone", c: [50.18, -4.27] },
        { n: "Beachy", c: [50.74, 0.13] },
        { n: "Start", c: [50.22, -3.62] },
        { n: "Wolf", c: [50.06, -5.59] },
        { n: "Bass", c: [56.08, -2.64] },
    ];
    lighthouses.forEach(({ n, c }) => {
        const mk = L.marker(c, {
            icon: L.divIcon({
                html: `<div class="text-yellow-400 text-base"><i class="fas fa-lighthouse"></i></div>`,
                className: "",
                iconSize: [20, 20],
                iconAnchor: [10, 10],
            }),
        }).addTo(map);
        mk.bindTooltip(n, { permanent: true, direction: "right", className: "lh-label" });
        lighthouseMarkers.set(n, mk);
    });

    async function connectMap() {
        if (!KEY) {
            await getKey();
            if (!KEY) {
                document.getElementById("status-text").textContent = "NO KEY";
                return;
            }
        }
        
        console.log("🌐 Connecting to AIS WebSocket...");
        const socket = new WebSocket("wss://stream.aisstream.io/v0/stream");
        
        socket.onopen = () => {
            console.log("🔌 WebSocket connected, sending subscription...");
            socket.send(JSON.stringify({
                Apikey: KEY,
                BoundingBoxes: [[[48, -6], [54, 6]]],
                FilterMessageTypes: ["PositionReport"],
            }));
        };
        
        let pending = [], timer = null;
        
        socket.onmessage = function(e) {
            if (isFull) return;
            let raw = e.data;
            if (raw instanceof Blob) {
                const r = new FileReader();
                r.onload = function() {
                    try { 
                        const parsed = JSON.parse(r.result);
                        console.log("📨 Raw AIS message:", parsed);
                        addToPending(parsed); 
                    } catch (_) {}
                };
                r.readAsText(raw);
                return;
            }
            try { 
                const parsed = JSON.parse(raw);
                console.log("📨 Raw AIS message:", parsed);
                addToPending(parsed); 
            } catch (_) {}
        };
        
        function addToPending(m) {
            messageCount++;
            
            // Debug: Log every 10th message
            if (messageCount % 10 === 0) {
                console.log(`📊 Received ${messageCount} messages, pending: ${pending.length}`);
            }
            
            // Extract data from AIS message structure
            let report = null;
            let meta = m.MetaData || {};
            
            // PositionReport can be directly in Message or nested
            if (m.Message && m.Message.PositionReport) {
                report = m.Message.PositionReport;
            } else if (m.Message && m.Message["PositionReport"]) {
                report = m.Message["PositionReport"];
            } else if (m.PositionReport) {
                report = m.PositionReport;
            } else {
                // Try to find any message type that contains position data
                for (const key of Object.keys(m.Message || {})) {
                    if (m.Message[key] && (m.Message[key].Latitude !== undefined || m.Message[key].latitude !== undefined)) {
                        report = m.Message[key];
                        break;
                    }
                }
            }
            
            if (!report) {
                console.warn("No position report found in message:", m);
                return;
            }
            
            const mmsi = String(report.UserID || meta.MMSI || report.MMSI || "");
            if (!mmsi) return;
            
            // Extract position data with multiple fallbacks
            const lat = report.Latitude ?? report.latitude ?? meta.latitude ?? null;
            const lng = report.Longitude ?? report.longitude ?? meta.longitude ?? null;
            
            if (lat === null || lng === null) {
                console.warn("Missing coordinates for MMSI:", mmsi, report);
                return;
            }
            
            // Extract vessel details
            const name = (report.Name || meta.ShipName || mmsi).toString().trim();
            const destination = (report.Destination || meta.Destination || "").toString().trim() || null;
            const speed = parseFloat(report.Sog ?? report.SOG ?? meta.sog ?? 0);
            const heading = parseFloat(report.Cog ?? report.COG ?? report.TrueHeading ?? meta.trueHeading ?? 0);
            
            // Log first few vessels to verify data
            if (ships.size < 5) {
                console.log(`🚢 Vessel ${mmsi}: ${name}, Speed: ${speed}kn, Heading: ${heading}°, Dest: ${destination}, Pos: ${lat}, ${lng}`);
            }
            
            pending.push({
                mmsi: mmsi,
                lat: parseFloat(lat),
                lng: parseFloat(lng),
                speed: speed,
                heading: heading,
                name: name,
                dest: destination,
                type: getVesselType(mmsi),
                lastSeen: Date.now(),
            });
        }
        
        socket.onclose = () => {
            console.log("🔌 WebSocket closed, reconnecting in 5s...");
            if (!isFull) setTimeout(connectMap, 5000);
        };
        
        socket.onerror = (err) => {
            console.error("WebSocket error:", err);
        };
        
        function processFast() {
            if (isFull) { clearTimeout(timer); return; }
            if (pending.length === 0) { timer = setTimeout(processFast, 100); return; }
            
            const batch = pending.splice(0, pending.length);
            console.log(`🔄 Processing batch of ${batch.length} vessels`);
            
            batch.forEach(d => {
                if (!d.lat || !d.lng) return;
                if (ships.size >= MAX_SHIPS) {
                    isFull = true;
                    document.getElementById("status-text").textContent = "FULL";
                    clearTimeout(timer);
                    return;
                }
                
                ships.set(d.mmsi, d);
                sendToBackend(d);
                
                const speed = parseFloat(d.speed) || 0;
                const visible = isTypeVisible(d.type) && (!filterBySpeed || speed > 0.5);
                
                if (markers.has(d.mmsi)) {
                    markers.get(d.mmsi)
                        .setLatLng([d.lat, d.lng])
                        .setIcon(createIcon(d.speed, d.heading, d.type));
                } else {
                    const mk = L.marker([d.lat, d.lng], { icon: createIcon(d.speed, d.heading, d.type) });
                    if (visible) mk.addTo(map);
                    mk.bindTooltip(d.name, { direction: "top", offset: [0, -8] });
                    mk.on("click", () => {
                        selected = d.mmsi;
                        map.flyTo([d.lat, d.lng], 9, { duration: 0.5 });
                        showRoute(d.mmsi);
                    });
                    markers.set(d.mmsi, mk);
                }
            });
            
            document.getElementById("ship-count").textContent = ships.size;
            renderShipList();
            updateFilterStats();
            if (!isFull) timer = setTimeout(processFast, 100);
        }
        
        processFast();
    }
    
    // Cleanup old ships every 5 minutes
    setInterval(() => {
        const now = Date.now();
        const timeout = 20 * 60 * 1000;
        ships.forEach((v, m) => {
            if (v.lastSeen && now - v.lastSeen > timeout) {
                if (markers.has(m)) {
                    map.removeLayer(markers.get(m));
                    markers.delete(m);
                }
                ships.delete(m);
            }
        });
        document.getElementById("ship-count").textContent = ships.size;
        renderShipList();
        updateFilterStats();
    }, 300000);
    
    // Initialize: Load ports first, then connect
    loadPortsFromDatabase().then(() => {
        addPortMarkers();
        connectMap();
    });
}

// ==========================================
// 📊 FLEET PAGE LOGIC
// ==========================================
if (isFleetPage) {
    const allVessels = new Map();
    let currentPage = 1, perPage = 25, totalPages = 1, isFull = false;

    function renderFleet() {
        const search = (document.getElementById("search")?.value || "").toLowerCase();
        const typeFilter = document.getElementById("type-filter")?.value || "all";
        const speedFilter = document.getElementById("speed-filter")?.value || "all";
        const sortBy = document.getElementById("sort-by")?.value || "speed";
        
        let vessels = [...allVessels.values()];
        if (search) vessels = vessels.filter(v => v.name.toLowerCase().includes(search) || v.mmsi.includes(search));
        if (typeFilter !== "all") vessels = vessels.filter(v => v.type === typeFilter);
        if (speedFilter === "moving") vessels = vessels.filter(v => parseFloat(v.speed) > 0.5);
        if (speedFilter === "fast") vessels = vessels.filter(v => parseFloat(v.speed) > 10);
        if (speedFilter === "stationary") vessels = vessels.filter(v => parseFloat(v.speed) < 0.5);
        
        if (sortBy === "name") vessels.sort((a, b) => a.name.localeCompare(b.name));
        else if (sortBy === "type") vessels.sort((a, b) => a.type.localeCompare(b.type));
        else vessels.sort((a, b) => (parseFloat(b.speed) || 0) - (parseFloat(a.speed) || 0));
        
        totalPages = Math.max(1, Math.ceil(vessels.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * perPage;
        const pageData = vessels.slice(start, start + perPage);
        const el = document.getElementById("fleet-list");
        
        if (allVessels.size === 0) {
            el.innerHTML = '<div class="p-10 text-center text-slate-400"><i class="fas fa-satellite-dish text-4xl mb-3 block animate-pulse"></i><p class="font-medium">Connecting to AIS stream...</p></div>';
        } else if (pageData.length === 0) {
            el.innerHTML = '<div class="p-10 text-center text-slate-400"><i class="fas fa-search text-3xl mb-3 block"></i>No vessels match filters</div>';
        } else {
            el.innerHTML = pageData.map((v, i) => {
                const ti = VESSEL_TYPES[v.type] || { color: "#94a3b8", icon: "fa-ship" };
                const speed = parseFloat(v.speed) || 0;
                const heading = parseFloat(v.heading) || 0;
                const sc = speed > 15 ? "text-emerald-600" : speed > 5 ? "text-blue-600" : speed > 0.5 ? "text-amber-600" : "text-slate-400";
                const si = speed > 10 ? "fa-bolt" : speed > 0.5 ? "fa-ship" : "fa-anchor";
                const st = speed > 10 ? "Fast" : speed > 0.5 ? "Moving" : "Anchored";
                return `<div class="fleet-row grid grid-cols-12 gap-4 px-5 py-3 items-center text-sm">
                    <div class="col-span-1 text-slate-400 font-mono text-xs">${start + i + 1}</div>
                    <div class="col-span-3 font-semibold text-slate-800 truncate"><span class="type-dot mr-2" style="background:${ti.color}"></span>${v.name}</div>
                    <div class="col-span-2"><span class="text-xs font-semibold" style="color:${ti.color}"><i class="fas ${ti.icon} mr-1"></i>${v.type}</span></div>
                    <div class="col-span-1 font-mono text-xs text-slate-500">${v.mmsi}</div>
                    <div class="col-span-1 text-center font-bold ${sc}">${speed.toFixed(1)}</div>
                    <div class="col-span-1 text-center text-slate-600">${heading.toFixed(0)}°</div>
                    <div class="col-span-2 text-slate-500 text-xs">${v.dest || "—"}</div>
                    <div class="col-span-1 text-center text-xs"><i class="fas ${si} mr-1" style="color:${sc}"></i>${st}</div>
                </div>`;
            }).join("");
        }
        
        document.getElementById("showing-text").textContent = vessels.length > 0 ? `Showing ${start+1}-${Math.min(start+perPage,vessels.length)} of ${vessels.length} vessels` : "No vessels";
        document.getElementById("vessel-count-display").textContent = allVessels.size + " vessels";
        
        const pe = document.getElementById("page-numbers");
        if (pe) {
            let h = "";
            let sp = Math.max(1, currentPage-2), ep = Math.min(totalPages, sp+4);
            if (ep-sp < 4) sp = Math.max(1, ep-4);
            for (let i = sp; i <= ep; i++) h += `<button class="page-btn ${i===currentPage?"active":""}" onclick="goToPage(${i})">${i}</button>`;
            pe.innerHTML = h;
        }
        
        if (document.getElementById("btn-first")) {
            ["btn-first","btn-prev"].forEach(id => document.getElementById(id).disabled = currentPage <= 1);
            ["btn-next","btn-last"].forEach(id => document.getElementById(id).disabled = currentPage >= totalPages);
        }
        
        const c = {};
        vessels.forEach(v => c[v.type] = (c[v.type]||0)+1);
        if (document.getElementById("stat-total")) document.getElementById("stat-total").textContent = vessels.length;
        if (document.getElementById("stat-container")) document.getElementById("stat-container").textContent = c["Container"]||0;
        if (document.getElementById("stat-cargo")) document.getElementById("stat-cargo").textContent = c["Cargo"]||0;
        if (document.getElementById("stat-tanker")) document.getElementById("stat-tanker").textContent = c["Tanker"]||0;
        if (document.getElementById("stat-cruise")) document.getElementById("stat-cruise").textContent = c["Cruise"]||0;
        if (document.getElementById("stat-bulk")) document.getElementById("stat-bulk").textContent = c["Bulk"]||0;
        if (document.getElementById("stat-other")) document.getElementById("stat-other").textContent = (c["Tug"]||0)+(c["Fishing"]||0);
    }

    window.renderFleet = renderFleet;
    window.goToPage = function(p) { currentPage = Math.max(1, Math.min(p, totalPages)); renderFleet(); };
    window.prevPage = function() { if (currentPage > 1) goToPage(currentPage - 1); };
    window.nextPage = function() { if (currentPage < totalPages) goToPage(currentPage + 1); };
    window.changePerPage = function() { perPage = parseInt(document.getElementById("per-page").value); currentPage = 1; renderFleet(); };

    async function connectFleet() {
        if (!KEY) {
            await getKey();
            if (!KEY) {
                if (document.getElementById("connection-status")) {
                    document.getElementById("connection-status").textContent = "NO KEY";
                }
                return;
            }
        }
        
        console.log("🌐 Fleet: Connecting to AIS WebSocket...");
        const socket = new WebSocket("wss://stream.aisstream.io/v0/stream");
        
        socket.onopen = () => {
            if (document.getElementById("connection-status")) {
                document.getElementById("connection-status").textContent = "LOADING...";
            }
            socket.send(JSON.stringify({
                Apikey: KEY,
                BoundingBoxes: [[[48, -6], [54, 6]]],
                FilterMessageTypes: ["PositionReport"],
            }));
        };
        
        socket.onmessage = function(e) {
            if (isFull) return;
            let raw = e.data;
            if (raw instanceof Blob) {
                const r = new FileReader();
                r.onload = function() {
                    try { addVessel(JSON.parse(r.result)); } catch(_) {}
                };
                r.readAsText(raw);
                return;
            }
            try { addVessel(JSON.parse(raw)); } catch(_) {}
        };
        
        function addVessel(m) {
            let report = null;
            let meta = m.MetaData || {};
            
            if (m.Message && m.Message.PositionReport) {
                report = m.Message.PositionReport;
            } else if (m.PositionReport) {
                report = m.PositionReport;
            } else {
                for (const key of Object.keys(m.Message || {})) {
                    if (m.Message[key] && (m.Message[key].Latitude !== undefined || m.Message[key].latitude !== undefined)) {
                        report = m.Message[key];
                        break;
                    }
                }
            }
            
            if (!report) return;
            
            const mmsi = String(report.UserID || meta.MMSI || "");
            if (!mmsi) return;
            
            if (allVessels.size >= MAX_SHIPS && !allVessels.has(mmsi)) {
                isFull = true;
                socket.close();
                finishLoad();
                return;
            }
            
            const lat = report.Latitude ?? report.latitude ?? meta.latitude ?? null;
            const lng = report.Longitude ?? report.longitude ?? meta.longitude ?? null;
            if (lat === null || lng === null) return;
            
            const vessel = {
                mmsi,
                lat: parseFloat(lat),
                lng: parseFloat(lng),
                speed: parseFloat(report.Sog ?? report.SOG ?? 0),
                heading: parseFloat(report.Cog ?? report.COG ?? report.TrueHeading ?? 0),
                name: (report.Name || meta.ShipName || mmsi).toString().trim(),
                dest: (report.Destination || meta.Destination || "").toString().trim() || null,
                type: getVesselType(mmsi),
                lastSeen: Date.now(),
            };
            
            allVessels.set(mmsi, vessel);
            sendToBackend(vessel);
            
            if (document.getElementById("vessel-count-display")) {
                document.getElementById("vessel-count-display").textContent = allVessels.size + " vessels";
            }
            if (allVessels.size % 10 === 0) renderFleet();
        }
        
        function finishLoad() {
            if (document.getElementById("connection-status")) {
                document.getElementById("connection-status").textContent = "LIVE";
                document.getElementById("connection-status").className = "text-emerald-500 font-semibold text-xs";
            }
            renderFleet();
        }
        
        socket.onclose = () => {
            if (!isFull && document.getElementById("connection-status")) {
                document.getElementById("connection-status").textContent = "RECONNECTING...";
                setTimeout(connectFleet, 3000);
            }
        };
        
        setTimeout(() => {
            if (!isFull) {
                isFull = true;
                socket.close();
                finishLoad();
            }
        }, 30000);
    }
    
    connectFleet();
}