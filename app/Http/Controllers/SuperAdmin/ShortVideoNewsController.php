<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Repository\ShortVideoNews\ShortVideoNewsInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortVideoNews;
use App\Models\Category;
use App\Models\Region;
use File;
use Auth;

class ShortVideoNewsController extends Controller
{
    Protected $ShortVideoNews;
    public function __construct(ShortVideoNewsInterface $ShortVideoNews)
    {
        $this->ShortVideoNews = $ShortVideoNews;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ShortvideoNews = $this->ShortVideoNews->getAll();
        return view('admins.short_video_news.index', compact('ShortvideoNews'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions    = Region::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $categories = Category::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        return view('admins.short_video_news.create', compact('categories', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' =>'required',
            'thumbnail_image' =>'required',
            'video_url' =>'required',
            'url_status' =>'required',
        ]);

        $data = $request->all();
        
        $thumbnail_image = '';
        if($request->hasFile('thumbnail_image'))
        {
            $file = $request->file('thumbnail_image');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $maxid = ShortVideoNews::max('id');
                $id     = !empty($maxid) ? $maxid + 1 : 1;
                $thumbnail_image = uniqid() . '_' . time() . '_' . $id . '.' . $extesion;
                $destinationPath = public_path('thumbnail_image/');
                $file->move($destinationPath, $thumbnail_image);
            
        }

        $data['created_by']      = Auth::user()->id;
        $data['thumbnail_image'] = $thumbnail_image;
        $video = $this->ShortVideoNews->store($data);

        \App\Helpers\LogActivity::addToLog('Short Video News Added Successfully.');
        if($video)
        {
            return redirect()->route('short-video-news.index')->with('success', 'Short Video News Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regions    = Region::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $categories = Category::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $edit = $this->ShortVideoNews->find($id);
        $subcategories = Category::where('parent_id', $edit->category_id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);

        $countries = Region::where('parent_id', $edit->region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        $states    = [];
        if(!empty($edit->country_region_id))
        {
            $states    = Region::where('parent_id', $edit->country_region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        }
        return view('admins.short_video_news.edit', compact('countries','states','regions', 'edit', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $this->validate($request, [
            'title' =>'required',
            'video_url' =>'required',
            'url_status' =>'required',
        ]);

        $image = $imagenull = '';
            if($request->hasFile('thumbnail_image'))
            {
                $oldimage = ShortVideoNews::where('id', $id)->value('thumbnail_image');
                if(!empty($oldimage))
                {
                    $arr = explode("/", $oldimage);
                    $fullpath = File::delete('thumbnail_image/' . $arr[count($arr) - 1]);
                    File::delete($fullpath);
                }

                $file = $request->file('thumbnail_image');
                $filename = $file->getClientOriginalName();
                $extesion = $file->getClientOriginalExtension();
                        $image = uniqid() . '_' . time() . '_' . $id . '.' . $extesion;
                        $destinationPath = public_path('thumbnail_image/');
                        $file->move($destinationPath, $image);
                   
            }
            else
            {
                $imagenull = ShortVideoNews::where('id', $id)->value('thumbnail_image');
            }
            
            $data['thumbnail_image'] = !empty($image) ? $image : $imagenull;
            $video = $this->ShortVideoNews->update($data, $id);

            \App\Helpers\LogActivity::addToLog('Short Video News Updated Successfully.');
        if($video)
        {
            return redirect()->route('short-video-news.index')->with('success', 'Short Video News Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
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
        $video = $this->ShortVideoNews->delete($id);

        \App\Helpers\LogActivity::addToLog('Short Video News Deleted Successfully.');
        if($video)
        {
            return redirect()->route('short-video-news.index')->with('success', 'Short Video News Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function statusChange($id)
    {
        $video = $this->ShortVideoNews->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Short Video News Status Updated Successfully.');
        if($video)
        {
            return redirect()->route('short-video-news.index')->with('success', 'Short Video News Status Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function isLiveStatus($id)
    {
        $video = $this->ShortVideoNews->isLiveStatus($id);
        
        
        if($video) {
            return redirect()->back()->with('success', 'Short Video News Live Status Updated Successfully');
        }

        return redirect()->back()->with('error', 'Short Video News Live Status has not been changed!');
    }
}

