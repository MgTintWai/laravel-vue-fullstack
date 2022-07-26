<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;

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
Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('/create_tag',[AdminController::class,'addTag']);
    Route::get('/get_tag',[AdminController::class,'getTag']);
    Route::post('/edit_tag',[AdminController::class,'editTag']);
    Route::post('/delete_tag',[AdminController::class,'deleteTag']);
    Route::post('/upload',[AdminController::class,'upload']);
    Route::post('/delete_image',[AdminController::class,'delete']);
    Route::post('/create_category',[AdminController::class,'addCategory']);
    Route::get('/get_category',[AdminController::class,'getCategory']);
    Route::post('/edit_category',[AdminController::class,'editCategory']);
    Route::post('/delete_category',[AdminController::class,'deleteCategory']);
    Route::post('/create_user',[AdminController::class,'createUser']);
    Route::get('/get_users',[AdminController::class,'getUser']);
    Route::post('/edit_admin',[AdminController::class,'editAdmin']);
    Route::post('/admin_login',[AdminController::class,'adminLogin']);

    // Routes for role
    Route::post('/create_role',[AdminController::class,'createRole']);
    Route::get('/get_role',[AdminController::class,'getRole']);
    Route::post('/edit_role',[AdminController::class,'editRole']);
    Route::post('/assign_role',[AdminController::class,'assignRole']);
    Route::get('/blogsdata',[AdminController::class, 'blogData']); // get the blog items

    // Blog
    Route::post('create_blog',[AdminController::class,'createBlog']);
    Route::post('delete_blog',[AdminController::class,'deleteBlog']);
    Route::get('blog_single/{id}',[AdminController::class,'singelBlogItem']);
    Route::post('update_blog/{id}',[AdminController::class,'updateBlog']);

});

Route::post('/createBlog',[AdminController::class, 'uploadEditorImage']);
Route::get('/slug',[AdminController::class, 'slug']);
Route::get('/blogdata',[AdminController::class, 'blogData']);


Route::get('/logout',[AdminController::class,'logout']);
Route::get('/',[AdminController::class,'index']);
Route::any('{slug}',[AdminController::class,'index']);



Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

Route::get('{slug}', function (){
    return view('welcome');
});
