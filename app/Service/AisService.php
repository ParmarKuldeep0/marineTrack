<?php
// app/Service/AisService.php

namespace App\Service;

use App\Models\Vessel;
use App\Models\VesselPosition;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AisService
{
    protected string $apiKey;
    protected string $wsUrl = 'wss://stream.aisstream.io/v0/stream';

    public function __construct()
    {
        $this->apiKey = config('services.ais.api_key');
    }

    public function processPosition(array $data): void
    {
        try {
            Log::info('Processing position for MMSI: ' . ($data['mmsi'] ?? 'unknown'));
            
            $vessel = Vessel::firstOrCreate(
                ['mmsi' => $data['mmsi']],
                [
                    'name' => $data['name'] ?? null,
                    'type' => $this->detectVesselType($data['name'] ?? ''),
                ]
            );

            $position = VesselPosition::create([
                'vessel_id' => $vessel->id,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'speed' => $data['speed'] ?? 0,
                'course' => $data['course'] ?? 0,
                'heading' => $data['heading'] ?? 0,
                'destination' => $data['destination'] ?? null,
                'eta' => $data['eta'] ?? null,
                'received_at' => now(),
            ]);
            
            Log::info('Position saved successfully for vessel: ' . $vessel->name . ' (ID: ' . $vessel->id . ')');
            
        } catch (\Exception $e) {
            Log::error('Failed to process position: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getActiveVessels(int $limit = 100000): array
    {
        $vessels = Vessel::with('latestPosition')
            ->whereHas('positions', function ($query) {
                $query->where('received_at', '>=', now()->subHour());
            })
            ->limit($limit)
            ->get();
            
        Log::info('Retrieved ' . $vessels->count() . ' active vessels');
        
        return $vessels->map(function ($vessel) {
            $pos = $vessel->latestPosition;
            return [
                'mmsi' => $vessel->mmsi,
                'name' => $vessel->name,
                'type' => $vessel->type,
                'latitude' => $pos?->latitude,
                'longitude' => $pos?->longitude,
                'speed' => $pos?->speed,
                'course' => $pos?->course,
                'heading' => $pos?->heading,
                'destination' => $pos?->destination,
                'updated' => $pos?->received_at?->diffForHumans(),
            ];
        })->toArray();
    }

    public function getPorts(): array
    {
        $portRows = VesselPosition::query()
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->where('received_at', '>=', now()->subHours(6))
            ->get()
            ->groupBy(function ($row) {
                return $this->normalizeDestinationName($row->destination);
            });

        if ($portRows->isEmpty()) {
            Log::info('No AIS ports found from recent vessel destinations');
            return [];
        }

        return $portRows
            ->map(function ($group) {
                return [
                    'latitude' => $group->avg('latitude'),
                    'longitude' => $group->avg('longitude'),
                ];
            })
            ->filter(function ($coords) {
                return $coords['latitude'] !== null && $coords['longitude'] !== null;
            })
            ->mapWithKeys(function ($coords, $destination) {
                return [
                    $destination => [
                        (float) $coords['latitude'],
                        (float) $coords['longitude'],
                    ],
                ];
            })
            ->toArray();
    }

    public function findPortCoordinates(string $destination): ?array
    {
        $normalized = $this->normalizeDestinationName($destination);
        if ($normalized === '') {
            return null;
        }

        $ports = $this->getPorts();
        if (isset($ports[$normalized])) {
            return $ports[$normalized];
        }

        foreach ($ports as $key => $coords) {
            if ($key === $normalized || str_contains($key, $normalized) || str_contains($normalized, $key)) {
                return $coords;
            }

            $keyTokens = array_filter(explode(' ', $key), fn ($token) => strlen($token) > 2);
            $destTokens = array_filter(explode(' ', $normalized), fn ($token) => strlen($token) > 2);
            $common = count(array_intersect($keyTokens, $destTokens));
            if ($common > 0 && $common >= min(count($keyTokens), count($destTokens)) / 2) {
                return $coords;
            }
        }

        return $this->geocodeDestinationPort($destination);
    }

    private function geocodeDestinationPort(string $destination): ?array
    {
        $cacheKey = 'ais_destination_coords:' . md5(strtolower($destination));

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($destination) {
            $search = $this->normalizeDestinationName($destination);
            if ($search === '') {
                return null;
            }

            $query = trim($search . ' port');
            $response = Http::withHeaders([
                'User-Agent' => 'ShipTracker/1.0 (https://example.com)',
            ])
                ->timeout(8)
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 0,
                ]);

            if (! $response->successful()) {
                return null;
            }

            $result = $response->json();
            if (! is_array($result) || count($result) === 0) {
                return null;
            }

            $first = $result[0];
            if (empty($first['lat']) || empty($first['lon'])) {
                return null;
            }

            return [(float) $first['lat'], (float) $first['lon']];
        });
    }

    private function normalizeDestinationName(?string $destination): string
    {
        if (!$destination) {
            return '';
        }

        $clean = trim(preg_replace('/[^A-Z0-9 ]+/', ' ', strtoupper($destination)));
        $clean = preg_replace('/\b(?:PORT OF|THE PORT OF|PORT|HARBOUR|HARBOR|TERMINAL|ANCHORAGE|BERTH|BAY|ZONE|PIER)\b/', ' ', $clean);
        return trim(preg_replace('/\s+/', ' ', $clean));
    }

    private function detectVesselType(string $name): string
    {
        $name = strtoupper($name);

        if (str_contains($name, 'MSC') || str_contains($name, 'MAERSK') || str_contains($name, 'CMA CGM')) {
            return 'Container';
        }
        if (str_contains($name, 'TANKER') || str_contains($name, 'LNG') || str_contains($name, 'CRUDE')) {
            return 'Tanker';
        }
        if (str_contains($name, 'CRUISE') || str_contains($name, 'CARNIVAL') || str_contains($name, 'PASSENGER')) {
            return 'Passenger';
        }
        if (str_contains($name, 'BULK') || str_contains($name, 'GRAIN') || str_contains($name, 'COAL')) {
            return 'Bulk';
        }
        if (str_contains($name, 'TUG') || str_contains($name, 'SVITZER') || str_contains($name, 'TOW')) {
            return 'Tug';
        }
        if (str_contains($name, 'F/V ') || str_contains($name, 'FISHING') || str_contains($name, 'TRAWLER')) {
            return 'Fishing';
        }

        return 'Cargo';
    }
}