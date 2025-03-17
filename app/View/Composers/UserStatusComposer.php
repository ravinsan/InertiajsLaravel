<?php
 
namespace App\View\Composers;

use App;
use Auth;
use Helpers;
use Carbon\Carbon;
use App\Models\Region;
use App\Models\Category;
use App\Models\NewsDetail;
use Illuminate\View\View;
 
class UserStatusComposer
{
    
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $country_slug = App::make('request')->segment(1);
        $state_slug   = App::make('request')->segment(2);

        if(!empty($country_slug) && $country_slug == 'india')
        {
            $country = Region::where('slug', $country_slug)->first();
        }

        if(!empty($state_slug) && $country_slug == 'india')
        {
            $state = Region::where('slug', $state_slug)->first();
        }

        $newsMenu    = Category::where('parent_id', 0)->where('frontend_menu_status', 1)->where('status', 1)
                        ->whereDoesntHave('children')
                        ->whereHas('newsDetails', function ($query) {
                            $query->where('status', 1);
                        })
                       ->get(['id', 'name', 'slug']);
                     
        $newsMenuSub = Category::where('parent_id', '>', 0)
                        ->where('frontend_menu_status', 1)
                        ->where('status', 1)
                        ->whereHas('parent', function ($query) {
                            $query->where('status', 1);
                            $query->where('frontend_menu_status', 1);
                        })
                        ->skip(0)->take(5)->get(['id', 'name', 'parent_id', 'slug']);

        $leastenews  = NewsDetail::with(['category', 'subcategory'])->where('status', 1)->orderBy('id', 'desc');
                       if(!empty($country->id))
                       {
                           $leastenews  = $leastenews->where('country_region_id', $country->id);
                       }  

                       if(!empty($state->id))
                       {
                           $leastenews  = $leastenews->where('state_region_id', $state->id);
                       }


        $leastenews  = $leastenews->first();                

        $news        = NewsDetail::with(['category', 'subcategory'])->where('status', 1)->orderBy('id', 'desc')->take(5);
                       if(!empty($country->id))
                       {
                           $news  = $news->where('country_region_id', $country->id);
                       }  

                       if(!empty($state->id))
                       {
                           $news  = $news->where('state_region_id', $state->id);
                       }
        $news        = $news->get();

        $breakingnews = NewsDetail::with(['category', 'subcategory'])->where('is_breaking_news', 1)->where('status', 1)->orderBy('id', 'desc');
                       if(!empty($country->id))
                       {
                           $breakingnews  = $breakingnews->where('country_region_id', $country->id);
                       }  

                       if(!empty($state->id))
                       {
                           $breakingnews  = $breakingnews->where('state_region_id', $state->id);
                       }
        $breakingnews = $breakingnews->get(['id','title','slug', 'category_id', 'subcategory_id']); 
        $getlatestfournews = NewsDetail::with(['category', 'subcategory'])->whereNotIn('category_id', [11])->where('status', 1)->orderBy('id', 'desc')->take(4)->skip(0);
                            if(!empty($country->id))
                            {
                                $getlatestfournews  = $getlatestfournews->where('country_region_id', $country->id);
                            }  

                            if(!empty($state->id))
                            {
                                $getlatestfournews  = $getlatestfournews->where('state_region_id', $state->id);
                            }
        $getlatestfournews = $getlatestfournews->get(['id','category_id', 'subcategory_id', 'title','slug', 'thumbnail_image', 'created_at']); 

        $data['newsMenu']     = $newsMenu;
        $data['newsMenuSub']  = $newsMenuSub;
        $data['leastenews']   = $leastenews;
        $data['news']         = $news;
        $data['breakingnews'] = $breakingnews;
        $data['getlatestfournews'] = $getlatestfournews;
        
        $view->with('data', $data);

        
    }
}