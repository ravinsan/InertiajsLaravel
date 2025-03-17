<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
use Hash;
use Auth;
use DB;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('admins.profile.index');
    }

    public function update(Request $request)
    {
        
        $this->validate($request, [
            
            'name'          => 'required',            
            'email'         => 'required|email|unique:users,email,'.Auth::user()->id,            
            'password'      => 'same:confirm-password',
            
        ]);

        $input = $request->all();
        
        if(!empty($request->business_priority))
        {
            $string = implode(',', $request->business_priority);
            $input['business_priority'] = $string;
        }

        
        if(!empty($input['password'])){ 

            $input['password'] = Hash::make($input['password']);

        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find(Auth::user()->id);

        $user->update($input);
        \App\Helpers\LogActivity::addToLog('User Profile Updated Successfully.');
        return redirect()->route('profile.index')->with(['success' => 'User Profile Updated Successfully.']);
    }
}
