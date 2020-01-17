<?php

namespace App\Http\Controllers\V1;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCourse(Course $model)
    {
        /* $url = url('uploads/slider').'/';
        $sliders = $model->select("sliders.*"
		    , DB::raw("CONCAT('{$url}', sliders.slider_name) as sliderName"))
        ->get(); */
        $courses = $model->all();
        return response()->json($courses);
    }
}
