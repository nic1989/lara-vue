<?php

namespace App\Http\Controllers\V1;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSlider(Slider $model)
    {
        $url = url('uploads/slider').'/';
        $sliders = $model->select("sliders.*"
		    , DB::raw("CONCAT('{$url}', sliders.slider_name) as sliderName"))
        ->get();
        //$sliders = $model->all();
        return response()->json(array('sliders'=>$sliders));
    }
}
