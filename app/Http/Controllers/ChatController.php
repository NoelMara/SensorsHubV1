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

        $systemPrompt = "You are SensorsHub AI, a tutor for second-year IT students learning sensors, microcontrollers (ESP32, Arduino, Raspberry Pi Pico), GPIO, wiring, and basic electronics.

Rules:
- Keep replies SHORT: 2-4 sentences or a short bullet list max, unless the student explicitly asks for more detail.
- Focus on ONE concept per reply. Do not list every topic you can help with.
- For simple greetings like 'hello', just greet back briefly and ask what they want to learn — do not dump a topic overview.
- Only give Arduino/MicroPython code when specifically requested.
- If a question is unrelated to electronics, politely redirect to electronics topics.
- If unsure, say so instead of guessing.";

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
                    'maxOutputTokens' => 200,
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