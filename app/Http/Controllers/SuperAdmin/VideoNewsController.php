<?php

namespace App\Http\Controllers\SuperAdmin;

use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Repository\VideoNews\VideoNewsInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideoNews;
use App\Models\Category;
use App\Models\Region;
use File;
use Auth;

class VideoNewsController extends Controller
{
    Protected $VideoNews;
    public function __construct(VideoNewsInterface $VideoNews)
    {
        $this->VideoNews = $VideoNews;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videoNews = $this->VideoNews->getAll();
        return view('admins.video_news.index', compact('videoNews'));
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
        return view('admins.video_news.create', compact('categories', 'regions'));
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
        ]);

        $data = $request->all();
        
        $thumbnail_image = '';
        if($request->hasFile('thumbnail_image'))
        {
            $file = $request->file('thumbnail_image');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
                $maxid = VideoNews::max('id');
                $id     = !empty($maxid) ? $maxid + 1 : 1;
                $thumbnail_image = uniqid() . '_' . time() . '_' . $id . '.' . $extesion;
                $destinationPath = public_path('thumbnail_image/');
                $file->move($destinationPath, $thumbnail_image);
            
        }

        $data['created_by']      = Auth::user()->id;
        $data['thumbnail_image'] = $thumbnail_image;
        $video = $this->VideoNews->store($data);

        \App\Helpers\LogActivity::addToLog('Video News Added Successfully.');
        if($video)
        {
            return redirect()->route('video-news.index')->with('success', 'Video News Added Successfully');
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
        $edit = $this->VideoNews->find($id);
        $subcategories = Category::where('parent_id', $edit->category_id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        
        $countries = Region::where('parent_id', $edit->region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        $states    = [];
        if(!empty($edit->country_region_id))
        {
            $states    = Region::where('parent_id', $edit->country_region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        }

        return view('admins.video_news.edit', compact('countries','states','regions','edit', 'categories', 'subcategories'));
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
        
        $image = $imagenull = '';
            if($request->hasFile('thumbnail_image'))
            {
                $oldimage = VideoNews::where('id', $id)->value('thumbnail_image');
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
                $imagenull = VideoNews::where('id', $id)->value('thumbnail_image');
            }
            
            $data['thumbnail_image'] = !empty($image) ? $image : $imagenull;
            $video = $this->VideoNews->update($data, $id);

            \App\Helpers\LogActivity::addToLog('Video News Updated Successfully.');
        if($video)
        {
            return redirect()->route('video-news.index')->with('success', 'Video News Updated Successfully');
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
        $video = $this->VideoNews->delete($id);

        \App\Helpers\LogActivity::addToLog('Video News Deleted Successfully.');
        if($video)
        {
            return redirect()->route('video-news.index')->with('success', 'Video News Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function statusChange($id)
    {
        $video = $this->VideoNews->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Video News Status Updated Successfully.');
        if($video)
        {
            return redirect()->route('video-news.index')->with('success', 'Video News Status Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function isLiveStatus($id)
    {
        $video = $this->VideoNews->isLiveStatus($id);
        
        \App\Helpers\LogActivity::addToLog('Video News Live Status Updated Successfully.');
        if($video) {
            return redirect()->back()->with('success', 'Video News Live Status Updated Successfully');
        }

        return redirect()->back()->with('error', 'Video News Live Status has not been changed!');
    }
}
