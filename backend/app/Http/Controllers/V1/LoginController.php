<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAuth(Request $request, User $model)
    {
        dd($request->all());
        //return response()->json(array('sliders'=>$sliders));
    }

    public function checkAuthLogin()
    {
        echo 'aaya';
    }
}
