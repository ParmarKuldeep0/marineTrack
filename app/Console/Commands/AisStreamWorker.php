<?php

namespace App\Console\Commands;

use App\Services\AisService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AisStreamWorker extends Command
{
    protected $signature = 'ais:poll';
    protected $description = 'Poll AIS data once and store positions';

    public function handle()
    {
        $this->info('🚢 Fetching AIS data...');

        try {
            // Use free AIS data
            $response = Http::get('https://data.aishub.net/ws.php', [
                'format' => 1,
                'output' => 'json',
                'compress' => 0,
                'latmin' => 48,
                'latmax' => 54,
                'lonmin' => -6,
                'lonmax' => 6,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $vessels = $data[1] ?? [];
                
                $count = 0;
                foreach ($vessels as $vessel) {
                    if (empty($vessel['MMSI'])) continue;
                    
                    app(AisService::class)->processPosition([
                        'mmsi' => (string) $vessel['MMSI'],
                        'name' => $vessel['NAME'] ?? null,
                        'latitude' => (float) $vessel['LATITUDE'],
                        'longitude' => (float) $vessel['LONGITUDE'],
                        'speed' => (float) ($vessel['SPEED'] ?? 0),
                        'course' => (float) ($vessel['COURSE'] ?? 0),
                        'heading' => (float) ($vessel['HEADING'] ?? 0),
                        'destination' => $vessel['DESTINATION'] ?? null,
                    ]);
                    $count++;
                }

                $this->info("✅ Processed {$count} vessels");
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}