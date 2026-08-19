<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
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
            'users' => User::whereIn('role', ['user', 'student'])->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'administrators' => User::where('role', 'administrator')->count(),
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
                $q->where('role', 'student');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('administrator.dashboard', compact('stats', 'recentUsers', 'recentSuggestions', 'recentComments'));
    }

    public function analytics()
    {
        $totalUsers = User::whereIn('role', ['user', 'student'])->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalClasses = Classroom::count();
        $totalContent = Sensor::count() + Project::count() + Product::count() + Video::count();
        $newThisMonth = User::where('created_at', '>=', now()->subDays(30))
            ->where('role', '!=', 'administrator')
            ->count();
        $bannedCount = User::whereNotNull('banned_at')->count();

        // User growth chart (last 30 days)
        $userGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $userGrowth[] = [
                'date' => $date,
                'count' => User::whereDate('created_at', $date)
                    ->where('role', '!=', 'administrator')
                    ->count(),
            ];
        }

        // Top classes (by student count + average score)
        $topClasses = Classroom::withCount('students')
            ->with(['assessments', 'quizzes'])
            ->get()
            ->map(function ($class) {
                $totalPoints = 0;
                $totalPossible = 0;

                foreach ($class->assessments as $assessment) {
                    $totalPossible += $assessment->points;
                    $totalPoints += $assessment->submissions()->whereNotNull('score')->sum('score');
                }
                foreach ($class->quizzes as $quiz) {
                    $totalPossible += $quiz->points;
                    $totalPoints += $quiz->submissions()->whereNotNull('score')->sum('score');
                }

                $avgScore = $totalPossible > 0 ? round(($totalPoints / $totalPossible) * 100, 1) : 0;

                return [
                    'name' => $class->name,
                    'section' => $class->section,
                    'instructor' => $class->instructor->name ?? 'Unknown',
                    'students_count' => $class->students_count,
                    'avg_score' => $avgScore,
                ];
            })
            ->sortByDesc('students_count')
            ->take(5)
            ->values();

        // Content breakdown
        $contentBreakdown = [
            'sensors' => Sensor::count(),
            'projects' => Project::count(),
            'videos' => Video::count(),
            'products' => Product::count(),
        ];

        return view('administrator.analytics', compact(
            'totalUsers', 'totalInstructors', 'totalClasses', 'totalContent',
            'newThisMonth', 'bannedCount', 'userGrowth', 'topClasses', 'contentBreakdown'
        ));
    }

    public function logs()
    {
        $logs = ActivityLog::latest()->paginate(10);
        return view('administrator.logs', compact('logs'));
    }

    public function backup()
    {
        if (!auth()->user()->isAdministrator()) {
            abort(403);
        }

        $backupPath = storage_path('app/backups');
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        $files = glob($backupPath . '/*.sql');
        $totalBackups = count($files);
        
        if ($totalBackups >= 5) {
            unlink($files[0]);
            $totalBackups = 4;
        }

        $tables = \DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        $tables = collect($tables)->sortBy(function($table) {
            return $table->tablename === 'users' ? 0 : 1;
        })->values()->all();
        
        $output = "-- SensorsHub Database Backup\n";
        $output .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
        
        $skipTables = ['migrations', 'sessions', 'cache', 'jobs'];
        foreach ($tables as $table) {
            $tableName = $table->tablename;
            if (in_array($tableName, $skipTables)) continue;
            $rows = \DB::table($tableName)->get();
            
            if ($rows->isEmpty()) continue;
            
            $output .= "-- Table: {$tableName}\n";
            
            foreach ($rows as $row) {
                $values = [];
                foreach ((array) $row as $value) {
                    if (is_null($value)) {
                        $values[] = 'NULL';
                    } elseif (is_bool($value)) {
                        $values[] = $value ? 'TRUE' : 'FALSE';
                    } elseif (is_int($value) || is_float($value)) {
                        $values[] = $value;
                    } else {
                        $values[] = "'" . str_replace("'", "''", $value) . "'";
                    }
                }
                $output .= "INSERT INTO {$tableName} VALUES (" . implode(', ', $values) . ");\n";
            }
            $output .= "\n";
        }
        
        $filename = 'sensorshub-backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
        file_put_contents($backupPath . '/' . $filename, $output);
        
        return response($output)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function downloadBackup($filename)
    {
        if (!auth()->user()->isAdministrator()) {
            abort(403);
        }
        
        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->download($path);
    }

    public function deleteBackup($filename)
    {
        if (!auth()->user()->isAdministrator()) {
            abort(403);
        }
        
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
        }
        
        return back()->with('success', 'Backup deleted.');
    }

    public function clearLogs()
    {
        \App\Models\ActivityLog::truncate();
        return back()->with('success', 'All activity logs have been cleared.');
    }
}