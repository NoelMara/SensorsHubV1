<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Suggestion;

class DashboardController extends Controller
{
    /**
     * Display the instructor dashboard
     */
    public function index()
    {
        $stats = [
            'users' => User::whereIn('role', ['user', 'student'])->count(),
            'suggestions' => Suggestion::count(),
            'pending_suggestions' => Suggestion::where('status', 'pending')->count(),
        ];

        $recentUsers = User::where('role', 'student')
            ->latest()
            ->take(5)
            ->get();

        $recentSuggestions = Suggestion::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('instructor.dashboard', compact('stats', 'recentUsers', 'recentSuggestions'));
    }
}
