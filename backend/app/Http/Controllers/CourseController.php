<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\SubQuiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Config;

class CourseController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('permission:course-list|course-create|course-edit|course-delete', ['only' => ['index','store']]);
        $this->middleware('permission:course-create', ['only' => ['create','store']]);
        $this->middleware('permission:course-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:course-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Course $model)
    {
        try {
            $data['searchValue'] = $request->input('search');
            $data['searchStatus'] = $request->input('status');
            $data['status'] = [0, 1];
            if (!empty($data['searchStatus']) && $data['searchStatus'] != 'All') {
                if ($data['searchStatus'] == 'active') {
                    $data['status'] = [1];
                } else {
                    $data['status'] = [0];
                }
            }
            
            if (!empty($data['searchValue']) || !empty($data['searchStatus'])) {
                $model->where(function ($query) use ($data) {
                    $model->where('name', 'like', $data['searchValue'] . '%');
                    $model->whereIn('status', $data['status']);
                });
            }

            $courses = $model->paginate(Config::get('constants.pagination'));

            return view('courses.index', ['courses' => $courses, 'searchValue' => $data['searchValue'], 'searchStatus' => $data['searchStatus']]);
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: Index');
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
            $clients = User::where(array('user_type' => 2, 'status' => 1))->pluck('name', 'id')->all();
            return view('courses.create', compact('clients'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        try {
            $filename = $this->saveFiles($request->file('video_file'), 'video', $request->client_id);
            $request->request->remove('video_file');
            Course::create(array_merge($request->all(), ['video_name' => $filename]));
            
            return redirect()->route('course.index')->withStatus(__('Course successfully created.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: store');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        try {
            $clients = User::where(array('user_type' => 2, 'status' => 1))->pluck('name', 'id')->all();
            return view('courses.edit', compact('course', 'clients'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        try {
            $filename = $course->video_name;
            if ($request->file('video_file')) {
                $this->removeOldVideoFileFromStorage($filename, 'video');
                $filename = $this->saveFiles($request->file('video_file'), 'video', $request->client_id);
                $request->request->remove('video_file');
            }

            $course->update(array_merge($request->all(), ['video_name' => $filename]));
            return redirect()->route('course.index')->withStatus(__('Course successfully updated.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //Sub course ids
            $subCourseIds = SubQuiz::where('course_id', $id)->pluck('id')->toArray();
            //Quiz Ids
            $quizIds = Quiz::where('course_id', $id)->pluck('id')->toArray();
            //Question Ids
            $questionIds = Question::whereIn('quiz_id', $id)->pluck('id')->toArray();

            //Remove options by question id
            Option::whereIn('question_id', $questionIds)->delete();
            //Remove questions by quiz id
            Question::whereIn('quiz_id', $quizIds)->delete();
            //Remove Quiz
            Quiz::where('course_id', $id)->delete();
            //Remove Sub course by course id
            SubQuiz::where('course_id', $id)->delete();

            $course = Course::find($id);
            $course->delete();

            return redirect()->route('course.index')->withStatus(__('Course successfully deleted.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Course controller. Function Name: destroy');
        }
    }
}
