<?php

use App\Http\Controllers\SuperAdmin\ShortVideoNewsController;
use App\Http\Controllers\SuperAdmin\LogActivityController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\SuperAdmin\NewsDetailController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\VideoNewsController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\NewsMenuController;
use App\Http\Controllers\SuperAdmin\UserProfileController;
use App\Http\Controllers\SuperAdmin\SeoPageController;
use App\Http\Controllers\SuperAdmin\RegionController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\RoleController;
use App\Http\Controllers\SuperAdmin\MenuController;
use App\Http\Controllers\SuperAdmin\PageController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* Category */ 
    Route::resource('categories', CategoryController::class);
    Route::get('categories/status/{id}', [CategoryController::class, 'statusChange'])->name('categories.status');
    Route::get('get-categories', [CategoryController::class,  'getCategory'])->name('category.get');
    Route::get('get-subcategories', [CategoryController::class,  'getSubCategory'])->name('subcategory.get');
    Route::get('categories/mega-menu-status/{id}', [CategoryController::class, 'megaMenustatusChange'])->name('categories.mega_menu_status');
    Route::get('categories/frontend-menu-status/{id}', [CategoryController::class, 'frontendMenustatusChange'])->name('categories.frontend_menu_status');
    Route::get('categories/page-design-status/{id}', [CategoryController::class, 'PageDesignstatusChange'])->name('categories.page_design_status');

    /* News Menu */ 
    Route::resource('news-menus', NewsMenuController::class);
    Route::get('news-menus/delete/{id}', [NewsMenuController::class, 'destroy'])->name('news-menus.delete');
    Route::get('news-menus/status/{id}', [NewsMenuController::class, 'statusChange'])->name('news-menus.status');
    Route::get('news-menus/mega-menu-status/{id}', [NewsMenuController::class, 'megaMenustatusChange'])->name('news-menus.mega.menu.status');
    Route::get('get-news-menus', [NewsMenuController::class,  'getNewsMenu'])->name('news-menus.get');
    Route::get('get-sub-news-menus', [NewsMenuController::class,  'getSubNewsMenu'])->name('sub-news-menus.get');
});

require __DIR__.'/auth.php';
