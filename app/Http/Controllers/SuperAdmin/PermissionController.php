<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\Permission\PermissionRequest;
use App\Repository\Permission\PermissionInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Menu;
use Auth;

class PermissionController extends Controller
{

    Protected $permission;
    public function __construct(PermissionInterface $permission)
    {
        $this->permission = $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->permission->getall();  
        return view('admins.permissions.index', compact('records'));
    }

    public function create()
    {
        $menus = Menu::where('parent_id', 0)->orderBy('name', 'ASC')->get();
        return view('admins.permissions.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $sh = $this->permission->store($data);  

        \App\Helpers\LogActivity::addToLog('Permission has been successfully created.');
        if($sh) {
            return redirect()->route('permissions.index')->with('success', 'Data has been successfully created!');
        }
        else{
            return redirect()->route('permissions.create')->with('error', 'Data has not been created!');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $permission = $this->permission->find($permission->id);
        $menus      = Menu::where('parent_id', 0)->orderBy('name', 'ASC')->get();
        $submenus   = Menu::where('parent_id', $permission->menu_id)->orderBy('name', 'ASC')->get();
        return view('admins.permissions.edit', compact('permission', 'menus', 'submenus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, Permission $Permission)
    {
        
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $sh = $this->permission->update($data, $Permission->id);

        \App\Helpers\LogActivity::addToLog('Permission has been successfully updated.');
        if($sh) {
            return redirect()->route('permissions.index')->with('success', 'Data has been successfully updated!');
        }
        else{
            return redirect()->route('permissions.edit')->with('error', 'Data has not been updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Permission = $this->permission->delete($id);

        \App\Helpers\LogActivity::addToLog('Permission has been successfully deleted.');
        if($Permission) {
            return redirect()->route('permissions.index')->with('success', 'Data has been successfully deleted!');
        }
        else{
            return redirect()->route('permissions.index')->with('error', 'Data has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $Permission = $this->permission->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Permission status has been successfully changed.');
        if($Permission) {
            return redirect()->route('permissions.index')->with('success', 'Permission status has been successfully changed!');
        }

        return redirect()->route('permissions.index')->with('error', 'Permission status has not been changed!');
    }
}
