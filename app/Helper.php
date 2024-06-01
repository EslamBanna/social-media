<?php

use App\Models\Notification;

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
}
