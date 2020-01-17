<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Option;

class Question extends Model
{
    public $fillable = ['quiz_id', 'question_type', 'question'];

    public function option() {
        return $this->hasMany(Option::class, 'question_id');
    }
}
