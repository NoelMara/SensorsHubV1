<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Project;
use App\Models\Sensor;
use App\Models\Suggestion;
use App\Models\User;
use App\Models\Video;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::where('role', 'user')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'sensors' => Sensor::count(),
            'projects' => Project::count(),
            'products' => Product::count(),
            'videos' => Video::count(),
            'suggestions' => Suggestion::count(),
            'pending_suggestions' => Suggestion::where('status', 'pending')->count(),
        ];

        $recentUsers = User::latest()->take(6)->get();
        $recentSuggestions = Suggestion::with('user')->latest()->take(5)->get();
        $recentComments = Comment::with(['user', 'suggestion'])
            ->whereHas('user', function($q) {
                $q->where('role', 'user');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentUsers', 'recentSuggestions', 'recentComments'));
    }

    public function logs()
    {
        $logs = ActivityLog::latest()->paginate(10);
        return view('super-admin.logs', compact('logs'));
    }

    public function backup()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $tables = \DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        
        $output = "-- SensorsHub Database Backup\n";
        $output .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
        
        foreach ($tables as $table) {
            $tableName = $table->tablename;
            $rows = \DB::table($tableName)->get();
            
            if ($rows->isEmpty()) continue;
            
            $output .= "-- Table: {$tableName}\n";
            
            foreach ($rows as $row) {
                $values = [];
                foreach ((array) $row as $value) {
                    if (is_null($value)) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . str_replace("'", "''", $value) . "'";
                    }
                }
                $output .= "INSERT INTO {$tableName} VALUES (" . implode(', ', $values) . ");\n";
            }
            $output .= "\n";
        }
        
        $filename = 'sensorshub-backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
        
        return response($output)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}