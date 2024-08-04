<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    public function compose(View $view)
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadNotificationsCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        $view->with('notifications', $notifications)
             ->with('unreadNotificationsCount', $unreadNotificationsCount);
    }
}
