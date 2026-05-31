<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vessel_positions', function (Blueprint $table) {
            if (Schema::hasColumn('vessel_positions', 'Lattitude')) {
                $table->renameColumn('Lattitude', 'latitude');
            }
            if (Schema::hasColumn('vessel_positions', 'Longitude')) {
                $table->renameColumn('Longitude', 'longitude');
            }
            if (Schema::hasColumn('vessel_positions', 'Speed')) {
                $table->renameColumn('Speed', 'speed');
            }
            if (Schema::hasColumn('vessel_positions', 'Destination')) {
                $table->renameColumn('Destination', 'destination');
            }
            if (Schema::hasColumn('vessel_positions', 'recived_at')) {
                $table->renameColumn('recived_at', 'received_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vessel_positions', function (Blueprint $table) {
            if (Schema::hasColumn('vessel_positions', 'latitude')) {
                $table->renameColumn('latitude', 'Lattitude');
            }
            if (Schema::hasColumn('vessel_positions', 'longitude')) {
                $table->renameColumn('longitude', 'Longitude');
            }
            if (Schema::hasColumn('vessel_positions', 'speed')) {
                $table->renameColumn('speed', 'Speed');
            }
            if (Schema::hasColumn('vessel_positions', 'destination')) {
                $table->renameColumn('destination', 'Destination');
            }
            if (Schema::hasColumn('vessel_positions', 'received_at')) {
                $table->renameColumn('received_at', 'recived_at');
            }
        });
    }
};
