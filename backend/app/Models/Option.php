<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $fillable = ['question_id', 'option_val', 'is_correct', 'answer'];
}
