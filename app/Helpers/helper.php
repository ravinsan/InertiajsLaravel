
<?php
use Illuminate\Support\Facades\Http;
use App\Models\ExpensePurpose;
use App\Models\RolePermission;
use App\Models\ShortVideoNews;
use Illuminate\Http\Request;
use App\Models\BasicSetting;
use App\Models\NewsDetail;
use App\Models\Permission;
use App\Models\VideoNews;
use App\Models\RoleMenu;
use App\Models\NewsMenu;
use App\Models\Category;
use App\Models\Region;
use App\Models\Page;
use App\Models\Menu;
use App\Models\SEO;




function wappMSGsend($phone, $textSMS, $type, $instance){
//Your client Id
$client_id = "11d9825cd7d32b765cb7d711970a2528";
//Your Instance Id
$instance = $instance;
//whatsapp numbers
$mobileNumber = '91'.$phone;
//Your message to send, Add URL encoding here.
$message = $textSMS;
//Prepare you post parameters
if($type=='image'){
$type="image";
$postData = array(
'client_id' => $client_id,
'instance' => $instance,
'type' => $type,
'number' => $mobileNumber,
'message' => $message,
'caption'=> 'Corpbiz'
);
}else{
$type="text";
$sendData['client_id'] = $client_id;
$sendData['instance'] = $instance;
$sendData['type'] = $type;
$sendData['number'] = $mobileNumber;
$sendData['message'] = $message;


$postData = $sendData;

}

//API URL
$url="https://marketing.corpbiz.io/api/send.php";
// init the resource
$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => "https://marketing.corpbiz.io/api/send.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $postData,
  
]);

$response = curl_exec($ch);
$err = curl_error($ch);

curl_close($ch);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }
}


function checkPermission($role_id,$slug){
   return RolePermission::getPermissionByslugAndId($role_id,$slug);
}

function checkUserPermission($user_id,$slug){
   return UserPermission::getUserPermissionByslugAndId($user_id,$slug);
}

function getAllPermissionByRoleId($role_id){
   return RolePermission::getAllCheckedPermissions($role_id);
}

function checkAccessByRoleAndPermission($role,$permission){
    return RolePermission::where('role_id',$role)->where('permission_id',$permission)->pluck('access')->toArray();
}


function checkAccessByUserAndPermission($user,$permission){
    return UserPermission::where('user_id',$user)->where('permission_id',$permission)->pluck('access')->toArray();
}


function getParentMenuByUserAndRole($role_id){
   return $rolePermission = RoleMenu::select('m.id','m.name','m.url', 'm.slug','m.icon_code', 'm.parent_id')->join('menus as m','m.id','=','role_menus.menu_id')->where('role_menus.role_id',$role_id)->where('m.parent_id', 0)->orderBy('m.order_id','ASC')->where('status', 1)->get();
}

function checkChieldRoutePermission($url){  
   
    $permissionData = Permission::where('url', $url)->pluck('id');
    
    $permission_id = (isset($permissionData[0]) && !empty($permissionData))?$permissionData[0]:'';    
    
    $user = Auth::user();
    $rolePermission = RolePermission::where('role_id', $user->role_id)->pluck('permission_id')->toArray();
    
    if(isset($permission_id) && !empty($permission_id)){
        if(isset($rolePermission) && in_array($permission_id,$rolePermission)){
            return true;
        }
    }
    return false;
  }


function getChildMenuByParentId($menu_id,$role_id){
    return $rolePermission = RoleMenu::select('m.id','m.name','m.url','m.slug','m.icon_code')->join('menus as m','m.id','=','role_menus.menu_id')->where('role_menus.role_id',$role_id)->where('m.parent_id',$menu_id)->where('status', 1)->orderBy('order_id', 'asc')->get();
}


function getPermissionByMenuID($menu_id){
    return Permission::where('sub_menu_id',$menu_id)->get();
   // return $rolePermission = RolePermission::select('m.id','m.name','m.url','permission_id','access')->join('menus as m','m.id','=','role_permissions.permission_id')->where('role_permissions.role_id',$role_id)->where('m.parent_id',$menu_id)->get();
}


   function getPermissionByParentMenuID($menu_id){
    return Permission::where('menu_id',$menu_id)->get();
   // return $rolePermission = RolePermission::select('m.id','m.name','m.url','permission_id','access')->join('menus as m','m.id','=','role_permissions.permission_id')->where('role_permissions.role_id',$role_id)->where('m.parent_id',$menu_id)->get();
}

function getSubMenuByParentId($menu_id){
    return $rolePermission = Menu::select('id','name','url')->where('parent_id',$menu_id)->where('status',1)->get();
}

function getParentMenu()
{
    return Category::with(['children' => function($query) {
        $query->where('status', 1)
              ->where('frontend_menu_status', 1)
              ->with(['singleNews']);
    }])->where('parent_id', 0)
      ->where('frontend_menu_status', 1)
      ->where('status', 1)
      ->get();
}

function getBreakingNewsMenu()
{
    return NewsDetail::with(['category', 'subcategory'])->where('is_breaking_news', 1)->where('status', 1)->first(['id','title','slug']);
}

function getBreakingNews()
{
    return NewsDetail::with(['category', 'subcategory'])->where('is_breaking_news', 1)->where('status', 1)->orderBy('id', 'desc')->get(['id','title','slug', 'category_id', 'subcategory_id']);
}

function liveNews()
{
    return VideoNews::where('is_live', 1)->where('status', 1)->orderBy('id', 'desc')->get();
}

function getLatestFourNews()
{
    return NewsDetail::with(['category', 'subcategory'])->whereNotIn('category_id', [11])->where('status', 1)->orderBy('id', 'desc')->take(4)->skip(0)->get(['id','category_id', 'subcategory_id', 'title','slug', 'thumbnail_image', 'created_at']);
}

function getCategoryNews($cat,$skip, $take)
{
    return NewsDetail::with("category")
        ->where("category_id", $cat)
        ->whereNull("subcategory_id")
        ->where("status", 1)
        ->orderBy("id", "desc")
        ->skip($skip)
        ->take($take)
        ->get();
}


function lifeStyleNews($skip, $take)
{
    return NewsDetail::with(['category', 'subcategory'])->where('category_id', 4)->where('subcategory_id', 6)->where('status', 1)->orderBy('id', 'desc')->skip($skip)->take($take)->get();
}

function lifeKhelNews($skip, $take)
{
    return NewsDetail::with(['category', 'subcategory'])->where('category_id', 4)->where('subcategory_id', 7)->where('status', 1)->orderBy('id', 'desc')->skip($skip)->take($take)->get();
}

function getEntertainmentNews($skip, $take)
{
    return NewsDetail::with('category')->where('category_id', 12)->whereNull('subcategory_id')->where('status', 1)->orderBy('id', 'desc')->skip($skip)->take($take)->get();
}

function getFooterNews()
{
    return Category::whereIn('id', [6, 7, 8, 12])->get();
}

function AddSeoData($id, $type)
{
        $obj = new SEO();
        $obj->page_id = $id;
        $obj->page_type = $type;
        $obj->save();
        return $obj;
}    

function Page()
{
    return Page::whereStatus(1)->get();
}

function latestTwoNews()
{
   return NewsDetail::with(['category', 'subcategory'])->whereNotIn('category_id', [11])->where('status', 1)->orderBy('id', 'desc')->take(2)->skip(0)->get(['id','category_id', 'subcategory_id', 'title','slug', 'thumbnail_image', 'created_at']);
}

function verticalVideoNews()
{
    return VideoNews::where('status', 1)->orderBy('id', 'desc')->get();
}

function getShortVideoNews()
{
    return ShortVideoNews::where('status', 1)->orderBy('id', 'desc')->get();
}

function getDataFromSetting($name){
    $result=null;
    $data=BasicSetting::where("name",$name)->first();
    if($data){
        $result=$data->value;
    }

    return $result;
}

function getRegions(){
    return Region::with(['children'])->where('slug', 'india')->where('status', 1)->where('frontend_menu_status', 1)->get(['id', 'name', 'slug']);
}

function getNewsCountwithRegion($region_id)
{
    $count = 0;
    $countinent = NewsDetail::where('region_id', $region_id)->where('status', 1)->count();
    $count = $countinent;
    
    if(empty($countinent)){
        $country = NewsDetail::where('country_region_id', $region_id)->where('status', 1)->count();        
        $count   = $country;
    }

    if(empty($country) && empty($countinent)){
        $state = NewsDetail::where('state_region_id', $region_id)->where('status', 1)->count();
        $count = $state;
    }

    return $count;
}

