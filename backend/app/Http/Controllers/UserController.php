<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Config;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('permission:users-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(Request $request, User $model)
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
            
            $query = $model->where('user_type', '3');
            if (!empty($data['searchValue']) || !empty($data['searchStatus'])) {
                $query->where(function ($query) use ($data) {
                    $query->where('name', 'like', $data['searchValue'] . '%');
                    $query->whereIn('status', $data['status']);
                });
            }

            $users = $query->paginate(Config::get('constants.pagination'));
            
            return view('users.index', ['users' => $users, 'searchValue' => $data['searchValue'], 'searchStatus' => $data['searchStatus']]);

        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: Index');
        }
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $clients = User::where(array('user_type' => 2, 'status' => 1))->pluck('name', 'id')->all();
            //$roles = Role::pluck('name', 'name')->all();

            return view('users.create', compact('clients'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: create');
        }
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request, User $model)
    {
        try {
            $user = $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());
            //$user->assignRole($request->input('roles'));
    
            return redirect()->route('user.index')->withStatus(__('User successfully created.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: store');
        }
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        try {
            $clients = User::where(array('user_type' => 2, 'status' => 1))->pluck('name', 'id')->all();
            //$roles = Role::pluck('name', 'name')->all();
            //$userRole = $user->roles->pluck('name', 'name')->all();
            
            return view('users.edit', compact('user', 'clients'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: edit');
        }
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user->update(
                $request->merge(['password' => Hash::make($request->get('password'))])
                    ->except([$request->get('password') ? '' : 'password']
            ));
    
            //DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            //$user->assignRole($request->input('roles'));
    
            return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: update');
        }
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : User controller. Function Name: destroy');
        }
    }
}
