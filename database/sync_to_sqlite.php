<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$sqlitePath = database_path('database.sqlite');
if (!file_exists($sqlitePath)) {
    touch($sqlitePath);
}

// Configure sqlite connection dynamically
config(['database.connections.sqlite_sync' => [
    'driver' => 'sqlite',
    'database' => $sqlitePath,
    'prefix' => '',
    'foreign_key_constraints' => false,
]]);

$tables = [
    'institution_settings',
    'grade_classes',
    'sections',
    'subjects',
    'classrooms',
    'teachers',
    'lesson_allocations',
    'timetable_slots',
    'duty_types',
    'duty_slots',
    'duty_assignments'
];

echo "Syncing MySQL -> SQLite ($sqlitePath)...\n";

// Disable foreign keys on SQLite
DB::connection('sqlite_sync')->statement('PRAGMA foreign_keys = OFF;');

// Ensure columns exist in SQLite
try { DB::connection('sqlite_sync')->statement('ALTER TABLE lesson_allocations ADD COLUMN is_combined INTEGER DEFAULT 0;'); } catch (\Exception $e) {}
try { DB::connection('sqlite_sync')->statement('ALTER TABLE lesson_allocations ADD COLUMN combined_group_id TEXT NULL;'); } catch (\Exception $e) {}
try { DB::connection('sqlite_sync')->statement('ALTER TABLE timetable_slots ADD COLUMN is_combined INTEGER DEFAULT 0;'); } catch (\Exception $e) {}
try { DB::connection('sqlite_sync')->statement('ALTER TABLE timetable_slots ADD COLUMN combined_group_id TEXT NULL;'); } catch (\Exception $e) {}

foreach ($tables as $t) {
    if (!Schema::connection('sqlite_sync')->hasTable($t)) {
        echo "Skipping non-existent table: $t\n";
        continue;
    }
    
    // Clear sqlite table
    DB::connection('sqlite_sync')->table($t)->delete();
    
    // Read from MySQL
    $rows = DB::connection('mysql')->table($t)->get();
    
    // Insert into SQLite in chunks
    $insertData = [];
    foreach ($rows as $row) {
        $insertData[] = (array)$row;
        if (count($insertData) >= 50) {
            DB::connection('sqlite_sync')->table($t)->insert($insertData);
            $insertData = [];
        }
    }
    if (!empty($insertData)) {
        DB::connection('sqlite_sync')->table($t)->insert($insertData);
    }
    
    $sqliteCount = DB::connection('sqlite_sync')->table($t)->count();
    $mysqlCount = DB::connection('mysql')->table($t)->count();
    echo "Table $t: MySQL ($mysqlCount) -> SQLite ($sqliteCount)\n";
}

DB::connection('sqlite_sync')->statement('PRAGMA foreign_keys = ON;');
echo "Database sync complete!\n";
