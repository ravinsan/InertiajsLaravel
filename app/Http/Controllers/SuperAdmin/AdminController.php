<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\User\PasswordRequest;
use App\Repository\User\UserInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BasicSetting;
use Illuminate\Support\Arr;
use App\Mail\ContactUsMail;
use App\Models\ContactUS;
use App\Models\User;
use Mail;
use File;
use Auth;
use Hash;

class AdminController extends Controller
{
    Protected $user;
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function setting()
    {
        return view('admins.setting.index');
    }

    function settingPost(Request $request)
    {
        $image = $minilogo = $domyimage = $favicon = '';
        if($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $image = uniqid().''.$extesion;
                $destinationPath = public_path('logo/');
                $file->move($destinationPath, $image);
            
            $data = BasicSetting::where("name", 'logo')->first();
            if($data){
                $data->value=asset('logo/'.$image);
                $data->save();
            }else{
                BasicSetting::create(['name'=>'logo', "value"=>asset('logo/'.$image)]);
            }
        }

        if($request->hasFile('mini-logo'))
        {
            $file = $request->file('mini-logo');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $minilogo = uniqid().''.$extesion;
                $destinationPath = public_path('mini-logo/');
                $file->move($destinationPath, $minilogo);
           
            $data = BasicSetting::where("name", 'mini-logo')->first();
            if($data){
                $data->value=asset('mini-logo/'.$minilogo);
                $data->save();
            }else{
                BasicSetting::create(['name'=>'mini-logo', "value"=>asset('mini-logo/'.$minilogo)]);
            }
        }

        if($request->hasFile('fev_icon'))
        {
            $file = $request->file('fev_icon');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $fev_icon = uniqid().''.$extesion;
                $destinationPath = public_path('fev_icon/');
                $file->move($destinationPath, $fev_icon);
           
            $data = BasicSetting::where("name", 'fev_icon')->first();
            if($data){
                $data->value=asset('fev_icon/'.$fev_icon);
                $data->save();
            }else{
                BasicSetting::create(['name'=>'fev_icon', "value"=>asset('fev_icon/'.$fev_icon)]);
            }
        }

        if($request->hasFile('domy-image'))
        {
            $file = $request->file('domy-image');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
            if($extesion=='jpg' || $extesion=='jpeg' || $extesion=='png' || $extesion=='svg')
            {
                $domyimage = uniqid().''.$extesion;
                $destinationPath = public_path('domy-image/');
                $file->move($destinationPath, $domyimage);
            }
            else
            {
                return redirect()->back()->with(['fail' => 'Image Extension is not valid, Please upload the jpg, jpeg and png file only.']);
            }

            $data = BasicSetting::where("name", 'domy-image')->first();
            if($data){
                $data->value=asset('domy-image/'.$domyimage);
                $data->save();
            }else{
                BasicSetting::create(['name'=>'domy-image', "value"=>asset('domy-image/'.$domyimage)]);
            }
        }

        foreach($request->input as $name=>$value){
            $name=str_replace("'","",$name);
            $data = BasicSetting::where("name",$name)->first();
            if($data){
                $data->value=$value;
                $data->save();
            }else{
                BasicSetting::create(['name'=>$name,"value"=>$value]);
            }
        }
        return redirect()->route('setting.index')->with('alert-success', "Setting updated successfully!");
    }

    public function contactUs()
    {
        $data = ContactUS::orderBy('id', 'DESC')->get();
        
        return view('admins.contactus.index', compact('data'));
    }

    public function contactUsReply($id)
    {
        $data = ContactUS::with('Reply')->find($id);
        return view('admins.contactus.reply', compact('data','id'));
    }

    public function contactUsReplyPost(Request $request)
    {
        $contact = ContactUS::find($request->id);

        $obj = new ContactUS();
        $obj->reply_id = $request->id;
        $obj->name     = Auth::user()->name;
        $obj->email    = $request->email;
        $obj->message  = $request->message;
        $obj->save();

        \App\Helpers\LogActivity::addToLog('Reply has been sent successfully.');
        Mail::to($request->email)->send(new ContactUsMail($contact));
        return redirect()->route('contact-us.index')->with('success', "Reply has been sent successfully!");
    }
}
