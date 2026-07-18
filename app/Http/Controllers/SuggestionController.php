<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestion;
use App\Helpers\ActivityLogHelper;
use App\Models\Comment;

class SuggestionController extends Controller
{
    // ─── Community View ───────────────────────────────────────────────
    public function community()
    {
        $suggestions = Suggestion::with(['user', 'comments'])
            ->latest()
            ->paginate(12);

        return view('suggestions.community', compact('suggestions'));
    }

    // ─── My Suggestions ───────────────────────────────────────────────
    public function mySuggestions()
    {
        $suggestions = auth()->user()->suggestions()->latest()->get();
        return view('user.suggestions', compact('suggestions'));
    }

    // ─── Show Single Suggestion ───────────────────────────────────────
    public function show(Suggestion $suggestion)
    {
        // Admins and super_admins can view any suggestion
        if (in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            $suggestion->load(['user', 'comments.user']);
            return view('user.suggestions-show', compact('suggestion'));
        }

        // Owners can view their own suggestions
        if ($suggestion->user_id === auth()->id()) {
            $suggestion->load(['user', 'comments.user']);
            return view('user.suggestions-show', compact('suggestion'));
        }

        // Any authenticated user can view any suggestion (community access)
        $suggestion->load(['user', 'comments.user']);
        return view('user.suggestions-show', compact('suggestion'));
    }

    // ─── Store Suggestion ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty'  => 'nullable|string|in:Beginner,Intermediate,Advanced',
            'sensor_type' => 'nullable|string|max:255',
        ]);

       $suggestion = auth()->user()->suggestions()->create($request->only('title', 'description', 'difficulty', 'sensor_type'));
       ActivityLogHelper::log('created', 'suggestion', "submitted a suggestion '{$suggestion->title}'");

        return back()->with('success', 'Suggestion submitted successfully! We\'ll review it soon.');
    }

    // ─── Edit Suggestion ──────────────────────────────────────────────
    public function edit(Suggestion $suggestion)
    {
        abort_if($suggestion->user_id !== auth()->id(), 403);
        abort_if($suggestion->status !== 'pending', 403, 'You can only edit pending suggestions.');

        return view('user.suggestions-edit', compact('suggestion'));
    }

    // ─── Update Suggestion ────────────────────────────────────────────
    public function update(Request $request, Suggestion $suggestion)
    {
        abort_if($suggestion->user_id !== auth()->id(), 403);
        abort_if($suggestion->status !== 'pending', 403, 'You can only edit pending suggestions.');

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty'  => 'nullable|string|in:Beginner,Intermediate,Advanced',
            'sensor_type' => 'nullable|string|max:255',
        ]);

        $suggestion->update($request->only('title', 'description', 'difficulty', 'sensor_type'));

        return redirect()->route('dashboard.suggestions')
            ->with('success', 'Suggestion updated successfully.');
    }

    // ─── Delete Suggestion ────────────────────────────────────────────
    public function destroy(Suggestion $suggestion)
    {
        abort_if($suggestion->user_id !== auth()->id(), 403);
        abort_if($suggestion->status !== 'pending', 403, 'You can only delete pending suggestions.');

        $suggestion->delete();

        return back()->with('success', 'Suggestion deleted successfully.');
    }

    // ─── Store Comment (1 per user - updateOrCreate) ──────────────────
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

    // ─── Update Comment ───────────────────────────────────────────────
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