<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\Role\RoleRequest;
use App\Repository\Role\RoleInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Models\RoleMenu;
use App\Models\Role;
use App\Models\Menu;
use Str;
use Hash;
use Auth;

class RoleController extends Controller
{
    Protected $Role;

    public function __construct(RoleInterface $Role)
    {
        $this->Role = $Role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->Role->getAll();
        return view('admins.roles.index', compact('roles'));
    }

    public function create()
    {
        $role = Role::where('status',1)->get();
        $menu = Menu::where('status',1)->where('parent_id', 0)->get();
        return view('admins.roles.create', compact('menu','role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $id = (isset($request->id) && !empty($request->id))?$request->id:'';
        $post = $request->all();
        
        if(isset($id) && !empty($id)){
            request()->validate([
                'role' => 'required',
            ]);
        }else{
            request()->validate([
                'role' => 'required|unique:roles,name',
            ]);
        }
        $data['added_by']   = Auth::user()->id;
        $post               = $request->all();
        $role_level         = (isset($post['role_level']) && !empty($post['role_level'])) ? implode(',', $post['role_level']):"";       
        $data['name']       = $request->role;
        $data['slug']       = Str::slug($request->role, '-');
        $data['role_level'] = $role_level;
        
        try{

          $role = Role::addRoles($id,$data);

          RolePermission::where('role_id',$role->id)->delete();
          RoleMenu::where('role_id',$role->id)->delete();

           if(isset($post['menu']) &&  !empty($post['menu'])){
              foreach($post['menu'] as $k => $m){
                  $menuData['role_id'] = $role->id;
                  $menuData['menu_id'] = $m; 
                  $menuData['is_parent'] = 1; 
                  RoleMenu::addRoleMenu($role->id,$m,$menuData);
              }
          }


            if(isset($post['subMenu']) &&  !empty($post['subMenu'])){
              foreach($post['subMenu'] as $k => $sm){
                  $subMenuData['role_id'] = $role->id;
                  $subMenuData['menu_id'] = $sm; 
                  $subMenuData['is_parent'] = 0; 
                  RoleMenu::addRoleMenu($role->id,$sm,$subMenuData);
              }
          }

          if(isset($post['permission']) &&  !empty($post['permission'])){
              foreach($post['permission'] as $k => $p){
                  $roleData['role_id'] = $role->id;
                  $roleData['permission_id'] = $p; 
                  $roleData['access'] = (isset($post['access'][$p]) && !empty($post['access'][$p]))?implode(',', $post['access'][$p]):"";
                  RolePermission::addRolePermissions($role->id,$p,$roleData);
              }
          }
          \App\Helpers\LogActivity::addToLog('Role has been successfully created.');
          return redirect()->route('roles.index')->with('success', 'Role has been successfully created!');
        }
        catch(\Exception $e){
            return redirect()->route('roles.create')->with('error', $e->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->Role->find($id);
        $checkedPermission = RolePermission::getAllCheckedPermissions($data->id)->toArray();
        $roleMenu = RoleMenu::where('role_id',$data->id)->pluck('menu_id')->toArray();

        $role = Role::where('status',1)->where('id','!=',$data->id)->get();
        $menu = Menu::getAllParentActiveMenu();
        
        $role_level = $data->role_level;
        $selected_role = explode(',', $role_level);
        return view('admins.roles.edit',compact('data','menu','role','checkedPermission','roleMenu', 'role_level', 'selected_role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $post = $request->all();
        request()->validate([
                'role' => 'required|unique:roles,name,'.$id
            ]);

        $post         = $request->all();
        $role_level   = (isset($post['role_level']) && !empty($post['role_level']))?implode(',', $post['role_level']):"";       
        $data['name'] = $request->role;
        $data['slug'] = Str::slug($request->role, '-');
        $data['role_level'] = $role_level;
        $data['created_by'] = Auth::user()->id;
        
        try{
          $role = Role::addRoles($id,$data);

         RoleMenu::where('role_id',$role->id)->delete();

           if(isset($post['menu']) &&  !empty($post['menu'])){
              foreach($post['menu'] as $k => $m){
                  $menuData['role_id'] = $role->id;
                  $menuData['menu_id'] = $m; 
                  $menuData['is_parent'] = 1; 
                  RoleMenu::addRoleMenu($role->id,$m,$menuData);
              }
          }


            if(isset($post['subMenu']) &&  !empty($post['subMenu'])){
              foreach($post['subMenu'] as $k => $sm){
                  $subMenuData['role_id'] = $role->id;
                  $subMenuData['menu_id'] = $sm; 
                  $subMenuData['is_parent'] = 0; 
                  RoleMenu::addRoleMenu($role->id,$sm,$subMenuData);
              }
          }

          RolePermission::where('role_id',$role->id)->delete();
          if(isset($post['permission']) &&  !empty($post['permission'])){
              foreach($post['permission'] as $k => $p){
                  $roleData['role_id'] = $role->id;
                  $roleData['permission_id'] = $p; 
                  $roleData['access'] = (isset($post['access'][$p]) && !empty($post['access'][$p]))?implode(',', $post['access'][$p]):"";
                  RolePermission::addRolePermissions($role->id,$p,$roleData);
              }
          }

          \App\Helpers\LogActivity::addToLog('Role has been successfully updated.');
          return redirect()->route('roles.index')->with('success', 'Role has been successfully updated!');
        }
        catch(\Exception $e){
            return redirect()->route('roles.edit', $id)->with('error', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->Role->delete($id);

        \App\Helpers\LogActivity::addToLog('Role has been successfully deleted.');
        if($delete) {
            return redirect()->route('roles.index')->with('success', 'Role has been successfully deleted!');
        }
        else{
            return redirect()->route('roles.index')->with('error', 'Role has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $Role = $this->Role->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Role status has been successfully changed.');
        if($Role) {
            return redirect()->route('roles.index')->with('success', 'Role status has been successfully changed!');
        }

        return redirect()->route('roles.index')->with('error', 'Role status has not been changed!');
    }

    public function reportingRole(Request $request)
    {
        $roles  = Role::where('status',1)->where('id', $request->role_id)->value('role_level');

        $records = Role::where('status',1)->whereIn('id', explode(",",$roles))->get();

        return view('admins.roles.reporting_head', compact('records'));
    }

    public function reportingUser(Request $request)
    {
        $records = User::where('status',1)->where('role_id', $request->reporting_head_id)->get();

        return view('admins.roles.reporting_head', compact('records'));
    }
}
