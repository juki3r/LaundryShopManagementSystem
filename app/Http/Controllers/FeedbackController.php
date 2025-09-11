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
            $feedback = Feedback::create([
                'user_id' => Auth::id(),           // link to logged-in user
                'comment' => $request->comment, // updated
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
}
