<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Project;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Redirect based on role
            if ($user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard.index');
        }
        
        // Public home data for guests only
        $featuredSensors = Sensor::where('is_active', true)->latest()->take(6)->get();
        $featuredProjects = Project::where('is_active', true)->where('is_featured', true)->latest()->take(4)->get();
        $latestVideos = Video::where('is_active', true)->latest()->take(4)->get();
        
        return view('home', compact('featuredSensors', 'featuredProjects', 'latestVideos'));
    }
}