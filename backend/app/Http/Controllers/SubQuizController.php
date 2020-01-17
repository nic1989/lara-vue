<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\SubQuiz;
use Illuminate\Http\Request;
use App\Http\Requests\SubQuizStoreRequest;
use App\Http\Requests\SubQuizUpdateRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Config;

class SubQuizController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('permission:subcourse-list|subcourse-create|subcourse-edit|subcourse-delete', ['only' => ['index','store']]);
        $this->middleware('permission:subcourse-create', ['only' => ['create','store']]);
        $this->middleware('permission:subcourse-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:subcourse-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, SubQuiz $model)
    {
        //try {
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

            $subcourses = $model->paginate(Config::get('constants.pagination'));

            return view('subcourses.index', ['subcourses' => $subcourses, 'searchValue' => $data['searchValue'], 'searchStatus' => $data['searchStatus']]);
        /* } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: Index');
        } */
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
            return view('subcourses.create', compact('courses'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubQuizStoreRequest $request)
    {
        try {
            $filename = $this->saveFiles($request->file('video_file'), 'subcourse', $request->course_id);
            $request->request->remove('video_file');
            SubQuiz::create(array_merge($request->all(), ['video_name' => $filename]));
            
            return redirect()->route('subcourse.index')->withStatus(__('Sub Quiz successfully created.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: store');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(SubQuiz $subcourse)
    {
        try {
            $courses = Course::where('status', 1)->pluck('title', 'id')->all();
            return view('subcourses.edit', compact('subcourse', 'courses'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, SubQuiz $subquiz)
    {
        try {
            $filename = $subquiz->video_name;
            if ($request->file('video_file')) {
                $this->removeOldVideoFileFromStorage($filename, 'subcourse');
                $filename = $this->saveFiles($request->file('video_file'), 'subquiz', $request->course_id);
                $request->request->remove('video_file');
            }

            $course->update(array_merge($request->all(), ['video_name' => $filename]));
            return redirect()->route('subcourse.index')->withStatus(__('SubQuiz successfully updated.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: update');
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
            $subquiz = SubQuiz::find($id);
            $subquiz->delete();

            return redirect()->route('subcourse.index')->withStatus(__('SubQuiz successfully deleted.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : SubQuiz controller. Function Name: destroy');
        }
    }
}
