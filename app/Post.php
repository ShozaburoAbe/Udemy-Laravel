<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function setImageAttribute($value)
    // {
    //     $this->attributes['image'] = asset($value);
    // }

    public function getImageAttribute($value)
    {
        return asset($value);
    }
}
