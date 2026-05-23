<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Sensor;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $suggestionsCount = $user->suggestions()->count();
        $savedProjectsCount = $user->savedProjects()->count();
        $recentSuggestions = $user->suggestions()->latest()->take(5)->get();
        $featuredSensors = Sensor::where('is_active', true)->latest()->take(6)->get();
        $featuredProjects = Project::where('is_active', true)->where('is_featured', true)->take(4)->get();
        
        return view('user.dashboard', compact('user', 'suggestionsCount', 'savedProjectsCount', 'recentSuggestions', 'featuredSensors', 'featuredProjects'));
    }
}
