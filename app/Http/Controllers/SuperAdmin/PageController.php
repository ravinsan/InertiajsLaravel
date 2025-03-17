<?php

namespace App\Http\Controllers\SuperAdmin;

use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Http\Requests\Page\PageRequest;
use App\Repository\Page\PageInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Page;
use Storage;
use File;

class PageController extends Controller
{
    protected $page;

    public function __construct(PageInterface $page)
    {
        $this->page = $page;
    }

    public function index()
    {
        $records = $this->page->getAll();        
        return view('admins.page.index', compact('records'));
    }
    
    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admins.page.create', compact('categories'));
    }

    public function store(PageRequest $request)
    {
        $data = $request->all();
        
        $image = '';
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $extesion = $file->getClientOriginalExtension();
                if($extesion=='jpg' || $extesion=='png' || $extesion=='jpeg' || $extesion=='svg')
                {
                    $image = uniqid().$filename;
                    $destinationPath = public_path('/frontend/image/');
                    $file->move($destinationPath, $image);
                }
                else
                {
                    $request->session()->flash('alert-danger', "Image Extension is not valid, Please upload the jpeg, jpg svg and png file only");
                    return redirect()->back();
                }
            }

        $data['image'] = asset('frontend/image/'.$image);
        $data['slug'] = $request->slug;
        $page = $this->page->store($data);

        \App\Helpers\LogActivity::addToLog('Page added successfully.');
        if($page) {
            AddSeoData($page->id, 0);
            return redirect()->route('pages.index')->with(['success' => 'Page added successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to add page.']);
    }

    public function edit($id)
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $edit = $this->page->find($id);
        return view('admins.page.edit', compact('edit', 'categories'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $data = $request->all();
        $image ='';
        
       
            if($request->hasFile('image'))
            {
                $oldimage = Page::where('id', $page->id)->value('image');
                $arr = explode("/", $oldimage);
                $fullpath = public_path('/frontend/image/' . $arr[count($arr) - 1]);
                
                File::delete($fullpath);

                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $extesion = $file->getClientOriginalExtension();
                    if($extesion=='jpg' || $extesion=='png' || $extesion=='jpeg' || $extesion=='svg')
                    {
                        $image = uniqid().$filename;
                        $destinationPath = public_path('frontend/image/');
                        $file->move($destinationPath, $image);
                    }
                    else
                    {
                        $request->session()->flash('alert-danger', "Image Extension is not valid, Please upload the jpeg, jpg svg and png file only");
                        return redirect()->back();
                    }
            }
            else
            {
                $imagenull = Page::where('id', $page->id)->value('image');
            }

        
        if($page->slug != $request->slug)
        {
            $checkSlug     = Page::where('slug', $request->slug)->first();
            $checkSlug ? $data['slug'] = $request->slug : $data['slug'] = $request->slug;
        }
        
        $data['image'] = $image ? asset("frontend/image/".$image) : $imagenull;
        $page = $this->page->update($data, $page->id);

        \App\Helpers\LogActivity::addToLog('Page updated successfully.');
        if($page) {
            return redirect()->route('pages.index')->with(['success' => 'Page updated successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to update page.']);
    }

    public function changeStatus(Request $request, $id)
    {
        $this->page->statusChange($id);
        \App\Helpers\LogActivity::addToLog('Status successfully changed.');
        return redirect()->route('pages.index')->with(['success' => 'Status successfully changed.']);
    }

    public function destroy($id)
    {
        $page = $this->page->delete($id);

        \App\Helpers\LogActivity::addToLog('Page Deleted successfully.');
        if($page) {
            return redirect()->route('pages.index')->with(['success' => 'Page Deleted successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to delete page.']);
    }
}
