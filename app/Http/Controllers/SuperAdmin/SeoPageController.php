<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\Seo\SeoRequest;
use App\Repository\Seo\SeoInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsDetail;
use App\Models\NewsMenu;
use App\Models\Page;
use App\Models\SEO;


class SeoPageController extends Controller
{
    protected $seo;

    public function __construct(SeoInterface $seo)
    {
        $this->seo = $seo;
    }

    public function index(Request $request)
    {
        $records = SEO::orderBy('id', 'desc')->paginate(10);
        if($request->ajax()){
            $search = 0;
            $type  = 0;
            $pages            = Page::where('status', 1)->where('slug', 'like', '%'.$request->seach_term.'%')->orWhere('title', 'like', '%'.$request->seach_term.'%')->first();
            $categories       = NewsMenu::where('slug', 'like', '%'.$request->seach_term.'%')->orWhere('name', 'like', '%'.$request->seach_term.'%')->where('parent_id', 0)->where('status', 1)->first();
            $subcategories    = NewsMenu::where('slug', 'like', '%'.$request->seach_term.'%')->orWhere('name', 'like', '%'.$request->seach_term.'%')->where('parent_id', '>', 0)->where('status', 1)->first();
            $newsdetails      = NewsDetail::where('slug', 'like', '%'.$request->seach_term.'%')->orWhere('title', 'like', '%'.$request->seach_term.'%')->where('status', 1)->first(); 
            
            if($pages)
            {
                $search = $pages->id;
                $type  = 0;
            }
            elseif($categories)
            {
                $search = $categories->id;
                $type  = 1;
            }
            elseif($subcategories)
            {
                $search = $subcategories->id;
                $type  = 1;
            }
            elseif($newsdetails)
            {
                $search = $newsdetails->id;
                $type  = 2;
            }
            
           
            if(isset($request->seach_term) && !empty($request->seach_term))
            {
             $records = SEO::Where('page_id', $search)
                    ->Where('page_type', $type)                
                    ->orderBy('id', 'desc')->paginate(10);
            }else{
                $records = SEO::query()
                ->when($request->seach_term, function($q)use($request){
                    $q->where('id', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('method', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('ip', 'like', '%'.$request->seach_term.'%')
                    ->orWhere('agent', 'like', '%'.$request->seach_term.'%');
                })
               ->orderBy('id', 'desc')->paginate(10);
            }
               
            return view('admins.seo.include.list', compact('records'))->render();
        }
         
        return view('admins.seo.index', compact('records'));
    }
    
    public function create()
    {
        $pages            = Page::where('status', 1)->get();
        $categories       = NewsMenu::where('parent_id', 0)->where('status', 1)->get();
        $subcategories    = NewsMenu::where('parent_id', '>', 0)->where('status', 1)->get();
        
        return view('admins.seo.create', compact('pages', 'categories', 'subcategories'));
    }

    public function store(SeoRequest $request)
    {
        $data = $request->all();
        $option = explode(',',$request->page_id);
        $check = SEO::where('page_id', $option[0])->where('page_type', $option[1])->first();
        if($check)
        {
            return back()->with('fail', "Sorry it has been already taken.");
        }
       
        $data['page_id'] = $option[0];
        $data['page_type'] = $option[1];
        
        $seo = $this->seo->store($data);

        \App\Helpers\LogActivity::addToLog('Seo Page added successfully.');
        if($seo) {
            return redirect()->route('seo-pages.index')->with(['success' => 'Seo Page added successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to add seo page.']);
    }

    public function edit($id)
    {
        $edit = $this->seo->find($id);
        if($edit->page_type == 0)
        {
            $data = Page::select('id', 'title as name', 'slug')->where('id', $edit->page_id)->where('status', 1)->first();
        }
        elseif($edit->page_type == 1)
        {
            $data = NewsMenu::select('id', 'name', 'slug')->where('id', $edit->page_id)->where('status', 1)->where('parent_id', 0)->first();
        }elseif($edit->page_type == 1)
        {
            $data = NewsMenu::select('id', 'name', 'slug')->where('id', $edit->page_id)->where('status', 1)->where('parent_id', '>', 0)->first();
        }else{
            $data = NewsDetail::select('id', 'title as name', 'slug')->where('id', $edit->page_id)->where('status', 1)->first();
        }

        $pages = Page::where('status', 1)->get();
        
        return view('admins.seo.edit', compact('edit', 'pages', 'data'));
    }

    public function update(SeoRequest $request, $id)
    {
        $data = $request->all();
        
        $seo = $this->seo->update($data, $id);

        \App\Helpers\LogActivity::addToLog('Seo Page updated successfully.');
        if($seo) {
            return redirect()->route('seo-pages.index')->with(['success' => 'Seo Page updated successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to update seo page.']);
    }

    public function destroy($id)
    {
        $seo = $this->seo->delete($id);

        \App\Helpers\LogActivity::addToLog('Seo Page Deleted successfully.');
        if($seo) {
            return redirect()->route('seo-pages.index')->with(['success' => 'Seo Page Deleted successfully.']);
        }

        return redirect()->back()->with(['fail' => 'Unable to delete seo page.']);
    }
}
