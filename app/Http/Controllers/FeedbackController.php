<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Store feedback from user.
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $userId = Auth::id();

            // Check if the user already submitted feedback
            $existingFeedback = Feedback::where('user_id', $userId)->first();
            if ($existingFeedback) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted feedback.',
                ], 403);
            }

            // Create new feedback
            $feedback = Feedback::create([
                'user_id' => $userId,
                'comment' => $request->comment,
                'rating'  => $request->rating,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully!',
                'data'    => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save feedback. ' . $e->getMessage()
            ], 500);
        }
    }

    public function myFeedback()
    {
        try {
            $userId = Auth::id();

            $feedback = Feedback::with('user:id,name')
                ->where('user_id', $userId)
                ->first();

            if (!$feedback) {
                return response()->json([
                    'success' => true,
                    'message' => 'You have not submitted feedback yet.',
                    'data'    => null
                ]);
            }

            return response()->json([
                'success' => true,
                'data'    => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch feedback. ' . $e->getMessage()
            ], 500);
        }
    }
}
