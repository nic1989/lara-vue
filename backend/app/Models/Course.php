<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'client_id', 'title', 'video_name', 'status'
    ];
}
