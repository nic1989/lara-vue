<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Option;

class Quiz extends Model
{
    public $fillable = ['course_id', 'subcourse_id', 'quiz_title'];

    public function question() {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function saveQuestions($questionRequest, $quizId)
    {
        foreach($questionRequest as $quizes) {
            //Multiple choice
            if (isset($quizes['select'])) {
                foreach($quizes['select'] as $key => $selectQst) {
                    $selectArr = $optArr = array();
                    
                    $selectArr['quiz_id'] = $quizId;
                    $selectArr['question_type'] = 'select';
                    $selectArr['question'] = trim($selectQst['question']);
                    
                    $question = Question::create($selectArr);
    
                    $o=0;
                    //Options
                    foreach($selectQst['option'] as $opkey => $opval) {
                        $optArr['option_val'] = trim($opval);
                        $optArr['is_correct'] = ($opkey == $selectQst['answer']) ? 1 : 0;
                        $optArr['question_id'] =  $question->id;
                        
                        Option::create($optArr);
                    }
                }
            }
            
            //Text
            if (isset($quizes['text'])) {
                foreach($quizes['text'] as $txtkey => $textQst) {
                    $textArr = $testAns = array();
                    
                    $textArr['quiz_id'] = $quizId;
                    $textArr['question_type'] = 'text';
                    $textArr['question'] = trim($textQst['question']);
    
                    $question = Question::create($textArr);
                    
                    $testAns['answer'] = trim($textQst['answer']);
                    $testAns['question_id'] =  $question->id;

                    Option::create($testAns);
                }
            }
        }

        return true;
    }
}
