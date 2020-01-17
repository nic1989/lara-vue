<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('permission:permission-list|permission-show', ['only' => ['index','show']]);
        $this->middleware('permission:permission-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data['searchValue'] = $request->input('search');
            
            $query = \DB::table('permissions AS pe')
                    ->select('pe.id', 'pe.name');

            if (!empty($data['searchValue'])) {
                $query->where(function ($query) use ($data) {
                    $query->where('name', 'like', $data['searchValue'] . '%');
                });
            }

            $permissions = $query->paginate(Config::get('constants.pagination'));

            return view('permissions.index', ['permissions' => $permissions, 'searchValue' => $data['searchValue']]);
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : PermissionController controller. Function Name: Index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
