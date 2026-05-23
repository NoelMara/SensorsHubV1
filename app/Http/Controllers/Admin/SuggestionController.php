<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use App\Models\Comment;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::with('user')->latest()->paginate(10);
        $stats = [
            'total'       => Suggestion::count(),
            'pending'     => Suggestion::where('status', 'pending')->count(),
            'reviewed'    => Suggestion::where('status', 'reviewed')->count(),
            'implemented' => Suggestion::where('status', 'implemented')->count(),
            'rejected'    => Suggestion::where('status', 'rejected')->count(),
        ];

        return view('admin.suggestions.index', compact('suggestions', 'stats'));
    }

    public function show(Suggestion $suggestion)
    {
        $suggestion->load(['user', 'comments.user']);

        return view('admin.suggestions.show', compact('suggestion'));
    }

    public function updateStatus(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,reviewed,implemented,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $suggestion->update($validated);

        return back()->with('success', 'Suggestion updated successfully.');
    }

    public function storeComment(Request $request, Suggestion $suggestion)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // One comment per user per suggestion (anti-spam)
        $suggestion->comments()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['body' => $validated['body']]
        );

        return back()->with('success', 'Comment added successfully.');
    }

    public function updateComment(Request $request, Suggestion $suggestion, Comment $comment)
    {
        abort_unless($comment->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment->update(['body' => $validated['body']]);

        return back()->with('success', 'Comment updated successfully.');
    }
}