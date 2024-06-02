<?php

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Events\SendNotification;
function addNotification($target_user_id, $user_id = null, $post_id = null, $content, $notification_type)
{
    Notification::create([
        'notification_type' => $notification_type,
        'post_id' => $post_id,
        'user_id' => $user_id,
        'target_user_id' => $target_user_id,
        'content' => $content,
        'shown' => 0
    ]);
    // fire event;
    event(new SendNotification('hello world'));
}

function myNotifications()
{
    $notifications = Notification::where('target_user_id', Auth::user()->id)
        ->where('shown', 0)
        ->get();
    Notification::where('target_user_id', Auth::user()->id)
        ->where('shown', 0)->update([
            'shown' => 1
        ]);
    return $notifications;
}
