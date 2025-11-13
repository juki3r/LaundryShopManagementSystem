<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseService;

class NotificationController extends Controller
{


    public function sendToOne($id)
    {
        try {
            // ✅ Find user or fail
            $user = User::findOrFail($id);

            $user = User::findOrFail($id);

            $unclaimedServiceTypes = $user->orders()
                ->where('claimed', 'NO')
                ->value('service_type');


            // ✅ Ensure FCM token exists
            if (!$user->expo_token) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User has no FCM token registered.'
                ], 400);
            }

            // ✅ Send notification via FirebaseService
            (new \App\Services\FirebaseService)->sendNotification(
                $user->expo_token,
                'Laundry Shop',
                'Your order is ready for ' . $unclaimedServiceTypes . '.'

            );

            return response()->json([
                'status'  => 'success',
                'message' => "Notification sent to {$user->name}"
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
