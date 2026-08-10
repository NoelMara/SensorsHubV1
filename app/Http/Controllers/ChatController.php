<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        // Only enrolled students can use the chatbot
        if (auth()->user()->role !== 'student') {
            return response()->json([
                'reply' => '👋 The AI chatbot is available for enrolled students only. Join a class to unlock this feature!'
            ]);
        }

        $message = trim($request->input('message'));

        $systemPrompt = "You are SensorsHub AI. Be BRIEF — 1-3 sentences max. Only give code when asked. For greetings, just say hi and ask what they want to learn. If unrelated to electronics/sensors, politely redirect.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key=' . env('GEMINI_API_KEY'),
            [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nStudent Question:\n" . $message]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 150,
                ],
            ]
        );

        $data = $response->json();

        if (!$response->successful()) {
            return response()->json([
                'reply' => '⚠️ AI service is temporarily unavailable. Please try again later.'
            ]);
        }

        $reply = $data['candidates'][0]['content']['parts'][0]['text']
            ?? 'Sorry, I could not generate a response.';

        return response()->json(['reply' => $reply]);
    }
}