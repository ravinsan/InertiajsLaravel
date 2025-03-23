<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Services\SlugService;
use App\Http\Requests\NewsMenu\CreateNewsMenuRequest;
use App\Http\Requests\NewsMenu\UpdateNewsMenuRequest;
use App\Repository\NewsMenu\NewsMenuInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsMenu;
use Inertia\Inertia;
use Hash;
use Auth;

class NewsMenuController extends Controller
{
    Protected $NewsMenu;

    public function __construct(NewsMenuInterface $NewsMenu)
    {
        $this->NewsMenu = $NewsMenu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->NewsMenu->getAll();
        return Inertia::render('News_menu/Index', compact('records'));
    }

    public function create()
    {
        $menus  = $this->NewsMenu->getNewsMenu();
        return Inertia::render('News_menu/Create', compact('menus'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewsMenuRequest $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;
        $data['slug']       = SlugService::createSlug(NewsMenu::class, 'slug', $request->slug);
        $NewsMenu = $this->NewsMenu->store($data);

        \App\Helpers\LogActivity::addToLog('News Menu has been successfully created.');
        if($NewsMenu) {
            AddSeoData($NewsMenu->id, 1);
            return redirect()->route('news-menus.index')->with('success', 'News Menu has been successfully created!');
        }
        else{
            return redirect()->route('news-menus.create')->with('error', 'News Menu has not been created!');
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
        $menus  = $this->NewsMenu->getNewsMenu();
        $NewsMenu = $this->NewsMenu->find($id);
        return Inertia::render('News_menu/Edit', compact('NewsMenu', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsMenuRequest $request, NewsMenu $newsMenu)
    {
        $data = $request->all();
        $data['name']                =  $request->name;
        $data['parent_id']           =  $request->parent_id ? $request->parent_id : 0;
        $NewsMenu = $this->NewsMenu->find($newsMenu->id);
        
        if($NewsMenu->slug     != $request->slug)
        {
            $checkSlug    = NewsMenu::where('slug', $request->slug)->first();
            $data['slug'] = $checkSlug ? SlugService::createSlug(NewsMenu::class, 'slug', $request->slug) : $request->slug;
        }

        $NewsMenu = $this->NewsMenu->update($data, $newsMenu->id);

        \App\Helpers\LogActivity::addToLog('News Menu has been successfully updated.');
        if($NewsMenu) {
            return redirect()->route('news-menus.index')->with('success', 'News Menu has been successfully updated!');
        }
        else{
            return redirect()->route('news-menus.edit', $newsMenu->id)->with('error', 'News Menu has not been updated!');
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
        $delete = $this->NewsMenu->delete($id);

        \App\Helpers\LogActivity::addToLog('News Menu has been successfully deleted.');
        if($delete) {
            return redirect()->route('news-menus.index')->with('success', 'News Menu has been successfully deleted!');
        }
        else{
            return redirect()->route('news-menus.index')->with('error', 'News Menu has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $NewsMenu = $this->NewsMenu->statusChange($id);

        \App\Helpers\LogActivity::addToLog('NewsMenu status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('news-menus.index')->with('success', 'NewsMenu status has been successfully changed!');
        }

        return redirect()->route('news-menus.index')->with('error', 'NewsMenu status has not been changed!');
    }

    public function megaMenustatusChange($id)
    {
        $NewsMenu = $this->NewsMenu->megaMenustatusChange($id);

        \App\Helpers\LogActivity::addToLog('NewsMenu Mega Menu status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('news-menus.index')->with('success', 'NewsMenu Mega Menu status has been successfully changed!');
        }

        return redirect()->route('news-menus.index')->with('error', 'NewsMenu Mega Menu status has not been changed!');
    }

    public function getNewsMenu()
    {
        $NewsMenu = $this->NewsMenu->getNewsMenu();
        return Inertia::render('News_menu/NewsMenu_list', compact('NewsMenu'));
    }

    public function getSubNewsMenu(Request $request)
    {
        $subNewsMenu = $this->NewsMenu->getSubNewsMenu($request->parent_news_menu_id);
        return Inertia::render('News_menu/sub_NewsMenu_list', compact('subNewsMenu'));
    }
}
