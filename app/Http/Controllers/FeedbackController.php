<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FeedbackController extends Controller
{
    // --- GET /api/feedback
    public function index(Request $request)
    {
        // Authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get all feedbacks with user name
        $feedbacks = Feedback::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($f) {
                return [
                    'id' => $f->id,
                    'user_id' => $f->user_id,
                    'user_name' => $f->user->name ?? 'Anonymous',
                    'feedback' => $f->feedback,
                    'rating' => $f->rating,
                ];
            });

        return response()->json(['feedbacks' => $feedbacks]);
    }

    // --- POST /api/feedback
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate request
        $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Prevent duplicate feedback from same user
        $existing = Feedback::where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json(['message' => 'You have already submitted feedback'], 400);
        }

        // Create feedback
        $feedback = Feedback::create([
            'user_id' => $user->id,
            'feedback' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'data' => [
                'id' => $feedback->id,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ],
            'message' => 'Feedback submitted successfully',
        ]);
    }
}
