<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CarlesUser;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    public function sendBlast()
    {
        (new FirebaseService)->sendNotificationToAll(
            'Carles Information',
            'This is a test broadcast to all users.'
        );

        return response()->json(['status' => 'success', 'message' => 'Notification sent to all users']);
    }

    public function sendToOne($id)
    {
        try {
            // ✅ Find user or fail
            $user = User::findOrFail($id);

            // ✅ Ensure FCM token exists
            if (!$user->fcm_token) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User has no FCM token registered.'
                ], 400);
            }

            // ✅ Send notification via FirebaseService
            (new \App\Services\FirebaseService)->sendNotification(
                $user->fcm_token,
                'Basta Carles the Best!',
                'Info: This is a test for information desimanation.'
            );

            return response()->json([
                'status'  => 'success',
                'message' => "Notification sent to {$user->fullname}"
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send notification.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
