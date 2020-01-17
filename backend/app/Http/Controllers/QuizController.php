<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\SubQuiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Config;
use DB;

class QuizController extends Controller
{
    public $quizmodel;
    public $questionmodel;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct(Quiz $quiz, Question $question)
    {
        $this->middleware('permission:quiz-list|quiz-create|quiz-edit|quiz-delete', ['only' => ['index','store']]);
        $this->middleware('permission:quiz-create', ['only' => ['create','store']]);
        $this->middleware('permission:quiz-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quiz-delete', ['only' => ['destroy']]);

        $this->quizmodel = $quiz;
        $this->questionmodel = $question;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Quiz $model)
    {
        try {
            $data['searchValue'] = $request->input('search');
            
            if (!empty($data['searchValue'])) {
                $model->where(function ($model) use ($data) {
                    $model->where('quiz_title', 'like', $data['searchValue'] . '%');
                });
            }

            $quizes = $model->paginate(Config::get('constants.pagination'));
            
            return view('quizes.index', ['quizes' => $quizes, 'searchValue' => $data['searchValue']]);

        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: Index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $courses = Course::where('status', 1)->pluck('title', 'id')->all();
            return view('quizes.create', compact('courses'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data['quiz_title'] = $request->quiz_title;
            $data['course_id'] = $request->course_id;
            $data['subcourse_id'] = $request->subcourse_id;

            $quiz = Quiz::create($data);

            $this->quizmodel->saveQuestions($request->all('quiz'), $quiz->id);
            
            return redirect()->route('quiz.index')->withStatus(__('Quiz created successfully'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: store');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        try {
            $courses = Course::where('status', 1)->pluck('title', 'id')->all();
            $questionDetail = Question::with(['option'])->where('quiz_id', $quiz->id)->get()->toArray();
            //dd($questionDetail);
            return view('quizes.edit', compact('quiz', 'questionDetail', 'courses'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        try {
            $data['quiz_title'] = $request->quiz_title;
            $data['course_id'] = $request->course_id;
            $data['subcourse_id'] = $request->subcourse_id;

            //Update Quiz
            Quiz::where('id', $quiz->id)->update($data);
            
            //Fetch question ids related to quiz id
            $questionIds = Question::where('quiz_id', $quiz->id)->pluck('id')->toArray();
            //Remove options by question id
            Option::whereIn('question_id', $questionIds)->delete();
            //Remove questions by quiz id
            Question::where('quiz_id', $quiz->id)->delete();

            $this->quizmodel->saveQuestions($request->all('quiz'), $quiz->id);
            
            return redirect()->route('quiz.index')->withStatus(__('Quiz updated successfully'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $id)
    {
        try {
            //Fetch question ids related to quiz id
            $questionIds = Question::where('quiz_id', $id)->pluck('id')->toArray();
            //Remove options by question id
            Option::whereIn('question_id', $questionIds)->delete();
            //Remove questions by quiz id
            Question::where('quiz_id', $id)->delete();
            //Remove Quiz
            Quiz::where('id', $id)->delete();
            
            return redirect()->route('quiz.index')->withStatus(__('Quiz successfully deleted.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Quiz controller. Function Name: destroy');
        }
    }

    public function getSubCourse($id, $subid = '')
    {
        $subcourses = SubQuiz::select('id', 'title')->where(array('course_id' => $id, 'status' => 1))->get();
        $html = '';
        $html .= '<option value="">Please choose Sub Course</option>';
        foreach($subcourses as $subcourse) {
            $selected = '';
            if ($subid == $subcourse->id) {
                $selected = 'selected';
            }
            $html .= '<option value="'.$subcourse->id.'" '.$selected.'>'.$subcourse->title.'</option>';
        }

        return $html;
    }
}
