<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\User\UserRequest;
use App\Repository\User\UserInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Role;
use Hash;
use Auth;
use File;

class UserController extends Controller
{
    Protected $user;
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $data = $this->user->getall();
        return view('admins.users.index', compact('data'));
    }

    public function create()
    {
        $roles = Role::where('status', 1)->get();
        return view('admins.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        // dd($data);
        $image = '';
        if($request->hasFile('profile_picture'))
        {
            $file = $request->file('profile_picture');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
            
                $image = uniqid().$filename;
                $destinationPath = public_path('/user/profile/');
                $file->move($destinationPath, $image);
        }

        $data['created_by'] = Auth::user()->id;
        $data['password']   = Hash::make($request->password);
        $data['profile_picture']   = asset('/user/profile/'.$image);
        
        $sh = $this->user->store($data);

        \App\Helpers\LogActivity::addToLog('User added successfully.');
        if($sh) {
            return redirect()->route('users.index')->with(['success' => "User added successfully."]);
        }

        return redirect()->back()->with(['error' => 'Unable to add User.']);
    }

    public function edit(User $User)
    {
        $roles = Role::where('status', 1)->get();
        $roporting_head = Role::where('id', $User->role_id)->value('role_level');
        $reporntings    = Role::where('status',1)->whereIn('id', explode(",",$roporting_head))->get();
        $users          = User::where('status',1)->where('role_id', $User->reporting_head_id)->get();

        return view('admins.users.edit', compact('User', 'roles', 'reporntings', 'users'));
    }

    public function update(Request $request, User $User)
    {
        $this->validate($request, [
            'name'=>'required',
        ]);


        $image = '';
        $data = $request->all();

        if($request->hasFile('profile_picture'))
        {
            $oldimage = User::where('id', $User->id)->value('profile_picture');

            $arr = explode("/", $oldimage);

            File::delete('user/profile/' . $arr[count($arr) - 1]);

            $file = $request->file('profile_picture');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $image = uniqid().$filename;
                $destinationPath = public_path('/user/profile/');
                $file->move($destinationPath, $image);            
        }else{
            $imagenull = User::where('id', $User->id)->value('profile_picture');
        }



        $data['created_by']      = Auth::user()->id;
        $data['profile_picture'] = !empty($image) ? asset('user/profile/'.$image) : $imagenull;

        if(!empty($data['password'])){

            $data['password'] = Hash::make($data['password']);

        }else{

            $data = Arr::except($data,array('password'));

        }

        $sh = $this->user->update($data, $User->id);

        \App\Helpers\LogActivity::addToLog('User updated successfully.');
        if($sh) {
            return redirect()->route('users.index')->with(['success' =>  "User updated successfully."]);
        }

        return redirect()->back()->with(['error' => 'Unable to update User.']);
    }

    public function destroy($id)
    {
        $user = $this->user->delete($id);

        \App\Helpers\LogActivity::addToLog('User deleted successfully.');
        if($user) {
            return redirect()->route('users.index')->with(['success' =>  "User deleted successfully."]);
        }

        return redirect()->back()->with(['error' => 'Unable to delete User.']);
    }

    public function changeStatus($id)
    {
        $user = $this->user->statusChange($id);

        \App\Helpers\LogActivity::addToLog('User status successfully changed.');
        if($user) {
            return redirect()->route('users.index')->with(['success' => "User status successfully changed."]);
        }

        return redirect()->back()->with(['fail' => 'Unable to change user status.']);
    }
}
