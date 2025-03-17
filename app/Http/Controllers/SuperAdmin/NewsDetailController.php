<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\NewsDetail\CreateNewsDetailRequest;
use App\Repository\NewsDetail\NewsDetailInterface;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsDetail;
use App\Models\NewsImage;
use App\Models\NewsVideo;
use App\Models\Category;
use App\Models\NewsMenu;
use App\Models\Region;
use Storage;
use Hash;
use Auth;
use File;

class NewsDetailController extends Controller
{
    Protected $NewsDetail;

    public function __construct(NewsDetailInterface $NewsDetail)
    {
        $this->NewsDetail = $NewsDetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->NewsDetail->getAll();
        return view('admins.news_details.index', compact('records'));
    }

    public function create()
    {
        $regions    = Region::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $categories = Category::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $menus      = NewsMenu::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get();
        return view('admins.news_details.create', compact('menus', 'categories', 'regions'));
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
            'title'            => 'required',
            'slug'             => 'required',
            'category_id'      => 'required|integer',
            'description'      => 'required',
            'thumbnail_image'  => 'required|mimes:jpeg,jpg,png,gif,avif,webp|max:10000',
        ],[
            'category_id.required' => 'Category filed is required'
        ]);

        $newsid = NewsDetail::max('id');       
        $id     = !empty($newsid) ? $newsid + 1 : 1;

        $data = $request->all();
         
        $thumbnail_image = '';
        

        if($request->hasFile('thumbnail_image')) {
         
            $filenamewithextension = $request->file('thumbnail_image')->getClientOriginalName();
            $filename  = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $request->file('thumbnail_image')->getClientOriginalExtension();
            $newsid    = NewsDetail::max('id');       
            $id        = !empty($newsid) ? $newsid + 1 : 1;
            $thumbnail_image = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
            Storage::disk('ftp')->put($thumbnail_image, fopen($request->file('thumbnail_image'), 'r+'));
        }

              
        $data['thumbnail_image'] = $thumbnail_image;
        $data['created_by']      = Auth::user()->id;
        $data['slug']            = SlugService::createSlug(NewsDetail::class, 'slug', $request->slug);
      
        $NewsDetail              = $this->NewsDetail->store($data);

        \App\Helpers\LogActivity::addToLog('NewsDetail has been successfully added.');
        if($NewsDetail) {
            AddSeoData($NewsDetail->id, 2);
            $images=array();
            if($files = $request->file('images')) {
                $files = is_array($files) ? $files : [$files];
            
                foreach($files as $file) {
                    if ($file->isValid()) {
                        $filenamewithextension = $file->getClientOriginalName();
                        $filename  = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
            
                        $newsid = NewsDetail::max('id');       
                        $id = !empty($newsid) ? $newsid + 1 : 1;
            
                        $images = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
            
                        Storage::disk('ftp')->put($images, fopen($file, 'r+'));
            
                        $obj = new NewsImage();
                        $obj->news_detail_id = $NewsDetail->id;
                        $obj->image = $images;
                        $obj->image_url = asset('image/' . $images);
                        $obj->save();
                    }
                }
            }


            return redirect()->route('news-details.index')->with('success', 'NewsDetail has been successfully added!');
        }
        else{
            return redirect()->back()->with('danger', 'NewsDetail has not been added!');
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
        $regions    = Region::where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $categories = Category::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        $menus      = NewsMenu::where('status', '1')->where('parent_id', 0)->orderBy('name', 'ASC')->get(['id', 'name']);
        
        $edit       = $this->NewsDetail->find($id);
        
        $submenus   = NewsMenu::where('parent_id', $edit->news_menu_id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        $subcategories = Category::where('parent_id', $edit->category_id)->orderBy('name', 'ASC')->get(['id', 'name', 'parent_id']);
        
        $countries = Region::where('parent_id', $edit->region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        $states    = [];
        if(!empty($edit->country_region_id))
        {
            $states    = Region::where('parent_id', $edit->country_region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        }
        return view('admins.news_details.edit', compact('edit', 'menus', 'submenus', 'categories', 'subcategories', 'regions', 'countries', 'states'));
    }

    public function show($id)
    {
        $NewsDetail = $this->NewsDetail->find($id);
        return view('admins.news_details.show', compact('NewsDetail'));
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
        $this->validate($request, [
            'title'            => 'required',
            'slug'             => 'required',
            'category_id'      => 'required',
            'description'      => 'required',
            'thumbnail_image'  => 'mimes:jpeg,jpg,png,gif,avif,webp|max:10000',
        ],[
            'category_id.required'     => 'Category is required',
        ]);

        $data = $request->all();
        $NewsDetail = $this->NewsDetail->find($id);
        
        $image = $imagenull = '';
           
            if($request->hasFile('thumbnail_image')) {
         
                $oldimage = NewsDetail::where('id', $id)->value('thumbnail_image');
                // if(!empty($oldimage))
                // {
                //     $arr = explode("/", $oldimage);
                //     $fullpath = File::delete('thumbnail_image/' . $arr[count($arr) - 1]);
                //     File::delete($fullpath);
                // }

                $filenamewithextension = $request->file('thumbnail_image')->getClientOriginalName();
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $extension = $request->file('thumbnail_image')->getClientOriginalExtension();
                $image = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
                Storage::disk('ftp')->put($image, fopen($request->file('thumbnail_image'), 'r+'));
            }
            else{
                $imagenull = NewsDetail::where('id', $id)->value('thumbnail_image');
            }

        if($NewsDetail->slug     != $request->slug)
        {
            $checkSlug    = NewsDetail::where('slug', $request->slug)->first();
            $data['slug'] = $checkSlug ? SlugService::createSlug(NewsDetail::class, 'slug', $request->slug) : $request->slug;
        }

        $data['thumbnail_image'] = !empty($image) ? $image : $imagenull;
        $NewsDetail = $this->NewsDetail->update($data, $id);


        if($NewsDetail) {

            \App\Helpers\LogActivity::addToLog('NewsDetail has been successfully updated.');

            $images=array();
            if($files = $request->file('images')) {
                $files = is_array($files) ? $files : [$files];
            
                foreach($files as $file) {
                    if ($file->isValid()) {
                        $filenamewithextension = $file->getClientOriginalName();
                        $filename  = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
            
                        $newsid = NewsDetail::max('id');       
                        $id = !empty($newsid) ? $newsid + 1 : 1;
            
                        $images = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
            
                        Storage::disk('ftp')->put($images, fopen($file, 'r+'));
            
                        $obj = new NewsImage();
                        $obj->news_detail_id = $NewsDetail->id;
                        $obj->image = $images;
                        $obj->image_url = asset('image/' . $images);
                        $obj->save();
                    }
                }
            }

                //   if(!empty($request->videos_url) && count($request->videos_url) > 0)
                //   {
                //     foreach($request->videos_url as $video)
                //       {
                //           $obj = new NewsVideo();
                //           $obj->news_detail_id = $id;
                //           $obj->video_url     = $video;
                //           $obj->save();
                //       }
                //   }

            return redirect()->route('news-details.index')->with('success', 'NewsDetail has been successfully updated!');
        }
        else{
            return redirect()->back()->with('error', 'NewsDetail has not been updated!');
        }
    }

    public function deleteImage(Request $request)
    {
        
        $image = NewsImage::findOrFail($request->id);
        
        $imagePath = public_path('image/' . $image->image);
       
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $image->delete();
        \App\Helpers\LogActivity::addToLog('Image deleted successfully.');
        return response()->json(['success' => 'Image deleted successfully.']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->NewsDetail->delete($id);

        \App\Helpers\LogActivity::addToLog('NewsDetail has been successfully deleted.');
        if($delete) {
            return redirect()->route('news-details.index')->with('success', 'NewsDetail has been successfully deleted!');
        }
        else{
            return redirect()->back()->with('error', 'NewsDetail has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $NewsDetail = $this->NewsDetail->statusChange($id);

        \App\Helpers\LogActivity::addToLog('NewsDetail status has been successfully changed.');
        if($NewsDetail) {
            return redirect()->back()->with('success', 'NewsDetail status has been successfully changed!');
        }

        return redirect()->back()->with('error', 'NewsDetail status has not been changed!');
    }

    public function breakingNewsStatus($id)
    {
        $NewsDetail = $this->NewsDetail->breakingNewsStatus($id);

        \App\Helpers\LogActivity::addToLog('NewsDetail breaking news status has been successfully changed.');
        if($NewsDetail) {
            return redirect()->back()->with('success', 'NewsDetail breaking news status has been successfully changed!');
        }

        return redirect()->back()->with('error', 'NewsDetail   breaking news status has not been changed!');
    }
}
