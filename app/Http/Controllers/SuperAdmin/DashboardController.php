<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\Sensor;
use App\Models\Suggestion;
use App\Models\User;
use App\Models\Video;

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

        return view('super-admin.dashboard', compact('stats', 'recentUsers', 'recentSuggestions'));
    }
}
