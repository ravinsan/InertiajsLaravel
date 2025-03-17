<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\Menu\MenuRequest;
use App\Repository\Menu\MenuInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Menu;
use Auth;                                   

class MenuController extends Controller
{

    Protected $menu;
    public function __construct(MenuInterface $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = $this->menu->getall(); 
        return view('admins.menu.index', compact('menus'));
    }

    public function create()
    {
        $menus  = Menu::where('parent_id', 0)->get();
        return view('admins.menu.create', compact('menus'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        $data = $request->all();
        $data['parent_id']   = !empty($request->parent_id) ? $request->parent_id : 0;
        $data['order_id']    = !empty($request->order_id) ? $request->order_id : 0;
        $data['slug']        = Str::slug($request->name, '-');
        $data['created_by']  = Auth::user()->id;
       
        $mn = $this->menu->store($data);  

        \App\Helpers\LogActivity::addToLog('Menu has been successfully created.');
        if($mn) {
            return redirect()->route('menu.index')->with('success', 'Menu has been successfully created!');
        }
        else{
            return redirect()->route('menu.index')->with('error', 'Menu has not been successfully created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $menus  = Menu::where('parent_id', 0)->get();
        $menu = $this->menu->find($menu->id);
        return view('admins.menu.edit', compact('menu', 'menus'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, Menu $Menu)
    {
        $data = $request->all();
        $data['parent_id']   = !empty($request->parent_id) ? $request->parent_id : 0;
        $data['order_id']    = !empty($request->order_id) ? $request->order_id : 0;
        $data['slug']        = Str::slug($request->name, '-');
        $data['created_by']  = Auth::user()->id;
        $mn = $this->menu->update($data, $Menu->id);

        \App\Helpers\LogActivity::addToLog('Menu has been successfully updated.');
        if($mn) {
            return redirect()->route('menu.index')->with('success', 'Menu has been successfully updated!');
        }
        else{
            return redirect()->route('menu.index')->with('error', 'Menu has not been successfully updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mn = $this->menu->delete($id);

        \App\Helpers\LogActivity::addToLog('Menu has been successfully deleted.');
        if($mn) {
            return redirect()->route('menu.index')->with('success', 'Menu has been successfully deleted!');
        }
        else{
            return redirect()->route('menu.index')->with('error', 'Menu has not been successfully deleted!');
        }
    }

    public function statusChange($id)
    {
        $mn = $this->menu->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Menu status has been successfully changed.');
        if($mn) {
            return redirect()->route('menu.index')->with('success', 'Menu status has been successfully changed!');
        }

        return redirect()->route('menu.index')->with('error', 'Menu status has not been successfully changed!');
    }

    public function fetchSubmenu(Request $request)
    {
        $data = Menu::where("parent_id", $request->menu_id)->get(["name", "id"]);
      
        return view("admins.menu.submenu", compact('data'));
    }
}
