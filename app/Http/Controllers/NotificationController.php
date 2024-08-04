<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationSubscription;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class NotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        $userId = Auth::id();
        $mkId = $request->id_mk;
        $subscribe = $request->subscribe;

        if ($subscribe) {
            // ตรวจสอบว่าผู้ใช้ได้สมัครแล้วหรือไม่
            $existingSubscription = NotificationSubscription::where('user_id', $userId)
                ->where('id_mk', $mkId)
                ->first();

            if (!$existingSubscription) {
                // เพิ่มการสมัคร
                NotificationSubscription::create([
                    'user_id' => $userId,
                    'id_mk' => $mkId,
                    'topic' => 'general' // เปลี่ยนเป็น topic ที่เหมาะสมถ้ามี
                ]);
                return response()->json(['message' => 'Subscribed successfully']);
            }

            return response()->json(['message' => 'Already subscribed']);
        } else {
            // ลบการสมัคร
            NotificationSubscription::where('user_id', $userId)
                ->where('id_mk', $mkId)
                ->delete();
            return response()->json(['message' => 'Unsubscribed successfully']);
        }
    }

    public function checkSubscription($mkId)
    {
        $userId = Auth::id();
        $isSubscribed = NotificationSubscription::where('user_id', $userId)
            ->where('id_mk', $mkId)
            ->exists();
        return response()->json(['isSubscribed' => $isSubscribed]);
    }

    public function getNotifications()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }


    public function markAsRead($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
    public function deleteNotification($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted successfully.'], 200);
        }

        return response()->json(['message' => 'Notification not found.'], 404);
    }
  

}
