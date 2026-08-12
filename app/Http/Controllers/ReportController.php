<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment;
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

         // Block admins from reporting
        if (auth()->user()->isAdministrator()) {
            return back()->with('error', 'Administrators cannot submit reports.');
        }

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
            
            if ($validated['reportable_type'] === 'suggestion') {
                $link = route('administrator.suggestions.show', $validated['reportable_id']);
            } else {
                $comment = Comment::find($validated['reportable_id']);
                $link = $comment 
                    ? route('administrator.suggestions.show', $comment->suggestion_id) 
                    : route('administrator.suggestions.index');
            }

             // Build a more detailed message
            $reporterName = auth()->user()->name;
            
            if ($validated['reportable_type'] === 'suggestion') {
                $suggestion = \App\Models\Suggestion::find($validated['reportable_id']);
                $reportedUserName = $suggestion?->user?->name ?? 'Deleted user';
                $contentPreview = $suggestion ? \Str::limit($suggestion->title, 50) : 'N/A';
                $message = "{$reporterName} reported {$reportedUserName}'s suggestion \"{$contentPreview}\" — Reason: {$validated['reason']}";
            } else {
                $comment = \App\Models\Comment::find($validated['reportable_id']);
                $reportedUserName = $comment?->user?->name ?? 'Deleted user';
                $contentPreview = $comment ? \Str::limit($comment->body, 50) : 'N/A';
                $message = "{$reporterName} reported {$reportedUserName}'s comment \"{$contentPreview}\" — Reason: {$validated['reason']}";
            }

                NotificationHelper::send(
                $admin->id,
                '🚩 New Report',
                $message,
                $link
            );
        }

        return back()->with('success', 'Report submitted. An administrator will review it.');
    }
}