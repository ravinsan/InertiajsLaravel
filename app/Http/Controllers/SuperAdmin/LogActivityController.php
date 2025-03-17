<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogActivity;

class LogActivityController extends Controller
{
    public function logActivity(Request $request)
    {

        $logs = LogActivity::orderBy('id', 'desc')->paginate(10);
        if($request->ajax()){
            $logs = LogActivity::query()
                ->when($request->seach_term, function($q)use($request){
                    $q->where('id', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('method', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('ip', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('agent', 'like', '%'.$request->seach_term.'%');
                })
            ->orderBy('id', 'desc')->paginate(10);
            return view('admins.log_activity.include.list', compact('logs'))->render();
        }

        return view('admins.log_activity.index',compact('logs'));

    }
}
