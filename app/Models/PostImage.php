<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;
    protected $fillable = ['post_id' ,'image'];
    public function getImageAttribute($value)
    {
        if ($value == null) {
            return asset('/defualt_profile_picture.png');
        } 
        else {
            return asset('/images/posts/' . $value);
        }
    }
}
