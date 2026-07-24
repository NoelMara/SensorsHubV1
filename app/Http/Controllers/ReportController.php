<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|in:suggestion,comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        // Map to full model path
        $type = $validated['reportable_type'] === 'suggestion' 
            ? 'App\Models\Suggestion' 
            : 'App\Models\Comment';

        $report = Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => $type,
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
        ]);

        // Notify administrator
        $admin = User::where('role', 'administrator')->first();
        if ($admin) {
            $itemType = $validated['reportable_type'] === 'suggestion' ? 'suggestion' : 'comment';
            NotificationHelper::send(
                $admin->id,
                '🚩 New Report',
                auth()->user()->name . ' reported a ' . $itemType . ' - ' . $validated['reason'],
                route('administrator.suggestions.index')
            );
        }

        return back()->with('success', 'Report submitted. An administrator will review it.');
    }
}