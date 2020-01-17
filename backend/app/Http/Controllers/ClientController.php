<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Config;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index','store']]);
        $this->middleware('permission:client-create', ['only' => ['create','store']]);
        $this->middleware('permission:client-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:client-delete', ['only' => ['destroy']]);
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
            
            $query = $model->where('user_type', '2');
            if (!empty($data['searchValue']) || !empty($data['searchStatus'])) {
                $query->where(function ($query) use ($data) {
                    $query->where('name', 'like', $data['searchValue'] . '%');
                    $query->whereIn('status', $data['status']);
                });
            }

            $client = $query->paginate(Config::get('constants.pagination'));

            return view('clients.index', ['clients' => $client, 'searchValue' => $data['searchValue'], 'searchStatus' => $data['searchStatus']]);
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: Index');
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
            $roles = Role::pluck('name', 'name')->all();
            return view('clients.create', compact('roles'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: create');
        }
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClientStoreRequest $request, User $model)
    {
        try {
            $user = $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());
            $user->assignRole($request->input('roles'));
    
            return redirect()->route('client.index')->withStatus(__('Client successfully created.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: store');
        }
        
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $user = User::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
    
            return view('clients.edit', compact('user', 'roles', 'userRole'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: edit');
        }
        
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClientUpdateRequest $request, $id)
    {
        try {
            if ($request->password == null) {
                $request->request->remove('password');
                $request->request->remove('password_confirmation');
            } else {
                $request->merge(['password' => Hash::make($request->get('password'))]);
                $request->request->remove('password_confirmation');
            }
            $user = User::find($id);
            $user->update($request->all());
    
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
    
            return redirect()->route('client.index')->withStatus(__('Client successfully updated.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: update');
        }
        
    }

    /**
     * Remove the specified client from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            //Remove client user
            User::where(array('parent_id' => $id))->delete();

            //Remove client
            $client = User::find($id);
            $client->delete();

            return redirect()->route('client.index')->withStatus(__('Client successfully deleted.'));
        } catch (\Exception $e) {
            \Log::info('error msg: ' . $e->getMessage() . ' Line no : ' . $e->getLine() . 'Module : Client controller. Function Name: destroy');
        }
    }
}
