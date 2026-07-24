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

        $type = $validated['reportable_type'] === 'suggestion' 
            ? 'App\Models\Suggestion' 
            : 'App\Models\Comment';

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => $type,
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
        ]);

        $admin = User::where('role', 'administrator')->first();
        if ($admin) {
            $itemType = $validated['reportable_type'] === 'suggestion' ? 'suggestion' : 'comment';
            
            $link = $validated['reportable_type'] === 'suggestion'
                ? route('administrator.suggestions.show', $validated['reportable_id'])
                : route('administrator.suggestions.index');

            NotificationHelper::send(
                $admin->id,
                '🚩 New Report',
                auth()->user()->name . ' reported a ' . $itemType . ' - ' . $validated['reason'],
                $link
            );
        }

        return back()->with('success', 'Report submitted. An administrator will review it.');
    }
}