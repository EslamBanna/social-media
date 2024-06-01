<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_type',
        'post_id',
        'user_id',
        'target_user_id',
        'content',
        'shown'
    ];
}
