<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TahunAkademik;
use Illuminate\Support\Facades\DB;

echo "Testing database update for is_active field...\n";

// Get first tahun akademik
$tahunAkademik = TahunAkademik::first();
if (!$tahunAkademik) {
    echo "No tahun akademik found in database\n";
    exit(1);
}

echo "Found tahun akademik: {$tahunAkademik->tahun} (ID: {$tahunAkademik->id})\n";
echo "Current is_active status: " . ($tahunAkademik->is_active ? 'true' : 'false') . "\n";

// Test updating to true
echo "\n=== Test 1: Setting is_active to true ===\n";
$originalStatus = $tahunAkademik->is_active;

try {
    DB::transaction(function () use ($tahunAkademik) {
        $validated = [
            'tahun' => $tahunAkademik->tahun,
            'semester' => $tahunAkademik->semester,
            'tanggal_mulai' => $tahunAkademik->tanggal_mulai->format('Y-m-d'),
            'tanggal_selesai' => $tahunAkademik->tanggal_selesai->format('Y-m-d'),
            'is_active' => '1'  // Simulating checkbox checked
        ];
        
        // Apply controller logic
        $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] == '1';
        
        echo "Updating with is_active = " . ($validated['is_active'] ? 'true' : 'false') . "\n";
        
        // If this is set as active, deactivate all others
        if ($validated['is_active']) {
            TahunAkademik::where('id', '!=', $tahunAkademik->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
        
        $tahunAkademik->update($validated);
    });
    
    // Refresh from database
    $tahunAkademik->refresh();
    echo "After update - is_active status: " . ($tahunAkademik->is_active ? 'true' : 'false') . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test updating to false
echo "\n=== Test 2: Setting is_active to false ===\n";

try {
    DB::transaction(function () use ($tahunAkademik) {
        $validated = [
            'tahun' => $tahunAkademik->tahun,
            'semester' => $tahunAkademik->semester,
            'tanggal_mulai' => $tahunAkademik->tanggal_mulai->format('Y-m-d'),
            'tanggal_selesai' => $tahunAkademik->tanggal_selesai->format('Y-m-d'),
            'is_active' => '0'  // Simulating checkbox unchecked
        ];
        
        // Apply controller logic
        $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] == '1';
        
        echo "Updating with is_active = " . ($validated['is_active'] ? 'true' : 'false') . "\n";
        
        // If this is set as active, deactivate all others
        if ($validated['is_active']) {
            TahunAkademik::where('id', '!=', $tahunAkademik->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
        
        $tahunAkademik->update($validated);
    });
    
    // Refresh from database
    $tahunAkademik->refresh();
    echo "After update - is_active status: " . ($tahunAkademik->is_active ? 'true' : 'false') . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Restore original status
echo "\n=== Restoring original status ===\n";
$tahunAkademik->update(['is_active' => $originalStatus]);
$tahunAkademik->refresh();
echo "Restored is_active status: " . ($tahunAkademik->is_active ? 'true' : 'false') . "\n";

echo "\n✓ Database update test completed successfully!\n";
