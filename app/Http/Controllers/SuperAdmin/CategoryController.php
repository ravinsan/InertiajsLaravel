<?php

namespace App\Http\Controllers\SuperAdmin;

use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Repository\Category\CategoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;
use Storage;
use File;
use Hash;
use Auth;

class CategoryController extends Controller
{
    Protected $Category;

    public function __construct(CategoryInterface $Category)
    {
        $this->Category = $Category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['pagen'] = $request->per_page ?? config('cms.pagination.perpage');
        $categories = $this->Category->getAll($data);
        return Inertia::render('Categories/Index', compact('categories'));
    }

    public function create()
    {
        $categories  = $this->Category->getCategory();
        return Inertia::render('Categories/Create', compact('categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {

        $data = $request->all();
        $image = '';
        if($request->hasFile('image')) {
         
            // $filenamewithextension = $request->file('image')->getClientOriginalName();
            // $filename  = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            // $extension = $request->file('image')->getClientOriginalExtension();
            // $newsid    = Category::max('id');       
            // $id        = !empty($newsid) ? $newsid + 1 : 1;
            // $image = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
            // Storage::disk('ftp')->put($image, fopen($request->file('image'), 'r+'));
            
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extesion = $file->getClientOriginalExtension();
            
                $image = uniqid().$filename;
                $destinationPath = public_path('image/');
                $file->move($destinationPath, $image);
            
        }
        
        $data['image']      = $image;
        $data['created_by'] = Auth::user()->id;
        $data['slug']       = SlugService::createSlug(Category::class, 'slug', $request->slug);
        $data['parent_id']  =  $request->parent_id ? $request->parent_id : 0;
        $Category = $this->Category->store($data);

        \App\Helpers\LogActivity::addToLog('Category has been successfully created.');

        if($Category) {
            return redirect()->route('categories.index')->with('success', 'Category has been successfully created!');
        }
        else{
            return redirect()->route('categories.create')->with('error', 'Category has not been created!');
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
        $parentCategory  = $this->Category->getCategory();
        $Category = $this->Category->find($id);
        return Inertia::render('Categories/edit', compact('Category', 'parentCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->all();
         $image = $imagenull = '';
           
            if($request->hasFile('image')) {
         
                $oldimage = Category::where('id', $id)->value('image');
                if(!empty($oldimage))
                {
                    $arr = explode("/", $oldimage);
                    $fullpath = File::delete('image/' . $arr[count($arr) - 1]);
                    File::delete($fullpath);
                }

                // $filenamewithextension = $request->file('image')->getClientOriginalName();
                // $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                // $extension = $request->file('image')->getClientOriginalExtension();
                // $image = uniqid() . '_' . time() . '_' . $id . '.' . $extension;
                // Storage::disk('ftp')->put($image, fopen($request->file('image'), 'r+'));

                $file = $request->file('image');
                $filename = $file->getClientOriginalName();
                $extesion = $file->getClientOriginalExtension();
            
                $image = uniqid().$filename;
                $destinationPath = public_path('image/');
                $file->move($destinationPath, $image);
            }
            else{
                $imagenull = Category::where('id', $id)->value('image');
            }

        $data['image']     = !empty($image) ? $image : $imagenull;
        $data['name']      =  $request->name;
        $data['parent_id'] =  $request->parent_id ? $request->parent_id : 0;
        $category = $this->Category->find($id);
        
        if($category->slug     != $request->slug)
        {
            $checkSlug    = Category::where('slug', $request->slug)->first();
            $data['slug'] = $checkSlug ? SlugService::createSlug(Category::class, 'slug', $request->slug) : $request->slug;
        }

        $Category = $this->Category->update($data, $id);

        \App\Helpers\LogActivity::addToLog('Category has been successfully updated.');
        if($Category) {
            return redirect()->route('categories.index')->with('success', 'Category has been successfully updated!');
        }
        else{
            return redirect()->route('categories.edit', $id)->with('error', 'Category has not been updated!');
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
        $delete = $this->Category->delete($id);

        \App\Helpers\LogActivity::addToLog('Category has been successfully deleted.');
        if($delete) {
            return redirect()->route('categories.index')->with('success', 'Category has been successfully deleted!');
        }
        else{
            return redirect()->route('categories.index')->with('error', 'Category has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $Category = $this->Category->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Category status has been successfully changed.');
        if($Category) {
            return redirect()->route('categories.index')->with('success', 'Category status has been successfully changed!');
        }

        return redirect()->route('categories.index')->with('error', 'Category status has not been changed!');
    }

    public function getCategory()
    {
        $category = $this->Category->getCategory();
        Inertia::render('Categories/category_list', compact('category'));
    }

    public function getSubCategory(Request $request)
    {
        $subcategory = $this->Category->getSubCategory($request->category_id);
        Inertia::render('Categories/sub_category_list', compact('subcategory'));
    }

    public function megaMenustatusChange($id)
    {
        $NewsMenu = $this->Category->megaMenustatusChange($id);

        \App\Helpers\LogActivity::addToLog('NewsMenu Mega Menu status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('categories.index')->with('success', 'NewsMenu Mega Menu status has been successfully changed!');
        }

        return redirect()->route('categories.index')->with('error', 'NewsMenu Mega Menu status has not been changed!');
    }

    public function frontendMenustatusChange($id)
    {
        $NewsMenu = $this->Category->frontendMenustatusChange($id);

        \App\Helpers\LogActivity::addToLog('Frontend Menu Show status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('categories.index')->with('success', 'Frontend Menu Show status has been successfully changed!');
        }

        return redirect()->route('categories.index')->with('error', 'Frontend Menu Show status has not been changed!');
    }

    public function PageDesignstatusChange($id)
    {
        $NewsMenu = $this->Category->pageDesignStatusChange($id);

        \App\Helpers\LogActivity::addToLog('Page Design status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('categories.index')->with('success', 'Frontend Menu Show status has been successfully changed!');
        }

        return redirect()->route('categories.index')->with('error', 'Page Design status has not been changed!');
    }
}
