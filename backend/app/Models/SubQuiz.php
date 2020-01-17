<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubQuiz extends Model
{
    public $fillable = ['course_id', 'title', 'video_name', 'status', 'sort_order'];
}
