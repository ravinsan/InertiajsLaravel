<?php

namespace App\Http\Controllers\SuperAdmin;

use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Http\Requests\Region\CreateRegionRequest;
use App\Http\Requests\Region\UpdateRegionRequest;
use App\Repository\Region\RegionInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use Hash;
use Auth;

class RegionController extends Controller
{
    Protected $Region;

    public function __construct(RegionInterface $Region)
    {
        $this->Region = $Region;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = $this->Region->getAll();
        return view('admins.regions.index', compact('regions'));
    }

    public function create()
    {
        $regions  = $this->Region->getRegion();
        
        return view('admins.regions.create', compact('regions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRegionRequest $request)
    {
        $data = $request->all();
        $parentid = !empty($request->country_id) ? $request->country_id : $request->parent_id; 
        
        $data['created_by'] = Auth::user()->id;
        $data['slug']       = SlugService::createSlug(Region::class, 'slug', $request->slug);
        $data['parent_id']  =  !empty($parentid) ? $parentid : 0;
        $Region = $this->Region->store($data);

        \App\Helpers\LogActivity::addToLog('Region has been successfully created.');

        if($Region) {
            return redirect()->route('regions.index')->with('success', 'Region has been successfully created!');
        }
        else{
            return redirect()->route('regions.create')->with('error', 'Region has not been created!');
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
        $regions  = $this->Region->getRegion();
        
        $Region = $this->Region->find($id);

        if(!empty($Region->parent_id) && $Region->parent_id > 0)
        {            
            $subparents = Region::where('id', $Region->parent_id)->first(['id', 'name', 'parent_id']);           
            
        }
        else{
            $subparents = [];
        }
        
        return view('admins.regions.edit', compact('Region', 'regions', 'subparents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdateRegionRequest $request, $id)
    // {
    //     $data = $request->all();
    //     $data['name']                =  $request->name;
    //     $data['parent_id']           =  $request->parent_id ? $request->parent_id : 0;
    //     $Region = $this->Region->find($id);
        
    //     if($Region->slug     != $request->slug)
    //     {
    //         $checkSlug    = Region::where('slug', $request->slug)->first();
    //         $data['slug'] = $checkSlug ? SlugService::createSlug(Region::class, 'slug', $request->slug) : $request->slug;
    //     }

    //     $Region = $this->Region->update($data, $id);

    //     \App\Helpers\LogActivity::addToLog('Region has been successfully updated.');
    //     if($Region) {
    //         return redirect()->route('regions.index')->with('success', 'Region has been successfully updated!');
    //     }
    //     else{
    //         return redirect()->route('regions.edit', $id)->with('error', 'Region has not been updated!');
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->Region->delete($id);

        \App\Helpers\LogActivity::addToLog('Region has been successfully deleted.');
        if($delete) {
            return redirect()->route('regions.index')->with('success', 'Region has been successfully deleted!');
        }
        else{
            return redirect()->route('regions.index')->with('error', 'Region has not been deleted!');
        }
    }

    public function statusChange($id)
    {
        $Region = $this->Region->statusChange($id);

        \App\Helpers\LogActivity::addToLog('Region status has been successfully changed.');
        if($Region) {
            return redirect()->route('regions.index')->with('success', 'Region status has been successfully changed!');
        }

        return redirect()->route('regions.index')->with('error', 'Region status has not been changed!');
    }

    public function getCountry(Request $request)
    {
        $country_list = Region::where('parent_id', $request->region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        return view('admins.regions.country_list', compact('country_list'));
    }

    public function getState(Request $request)
    {
        $subRegion = Region::where('parent_id', $request->country_region_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        return view('admins.regions.state_list', compact('subRegion'));
    }

    public function frontendMenustatusChange($id)
    {
        $NewsMenu = $this->Region->frontendMenustatusChange($id);

        \App\Helpers\LogActivity::addToLog('Frontend Menu Show status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('regions.index')->with('success', 'Frontend Menu Show status has been successfully changed!');
        }

        return redirect()->route('regions.index')->with('error', 'Frontend Menu Show status has not been changed!');
    }

    public function PageDesignstatusChange($id)
    {
        $NewsMenu = $this->Region->pageDesignStatusChange($id);

        \App\Helpers\LogActivity::addToLog('Page Design status has been successfully changed.');
        if($NewsMenu) {
            return redirect()->route('regions.index')->with('success', 'Frontend Menu Show status has been successfully changed!');
        }

        return redirect()->route('regions.index')->with('error', 'Page Design status has not been changed!');
    }

}
