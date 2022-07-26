<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index(Request $request){
        // first if you are loggedin and admin user......
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }

        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }
        // You are already loggedin... so check for if you are an admin user.....
        $user = Auth::user();
        if($user->userType == 'User'){
            return redirect('/login');
        }

        if($request->path() == 'login'){
            return redirect('/');
        }

        return $this->checkForPermission($user,$request);

    }

    public function checkForPermission($user,$request){
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if(!$permission) return view('welcome');

        foreach($permission as $p){
            if($p->name == $request->path()){
                if($p->read){
                    $hasPermission = true;
                }
            }
        }
        if($hasPermission) return view('welcome');;
        return view('notFound');

    }
    // logout
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request){
        // validate
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function editTag(Request $request){
        // validate
        $this->validate($request, [
            'tagName' => 'required',
            'id'      => 'required'
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }
    public function deleteTag(Request $request){
        // validate
        $this->validate($request, [
            'id'      => 'required'
        ]);
        return Tag::where('id', $request->id)->delete();
    }
    public function getTag(){
        return Tag::orderBy('id', 'desc')->get();
    }
    public function upload(Request $request){
            $this->validate($request, [
                'file'      => 'required|mimes:jpeg,jpg,png'
            ]);
            $picName = time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads'), $picName);
            return $picName;

    }
    public function delete(Request $request){
        $fileName = $request->imageName;
        $this->deleteFile($fileName, false);
        return 'done';

    }

    public function deleteFile($fileName, $hasFullPath = false){
        if(!$hasFullPath){
            $filePath = public_path().'/uploads/'.$fileName;
        }
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return;
    }

    public function addCategory(Request $request){
        // validate
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
    // Get Category

    public function getCategory(){
        return Category::orderBy('id', 'desc')->get();
    }
    // Edit Category

    public function editCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
    // Delete Category

    public function deleteCategory(Request $request){
        // fist delete the file from the original server

        $this->deleteFile($request->iconImage);
         // validate
         $this->validate($request, [
            'id'      => 'required'
        ]);
        return Category::where('id', $request->id)->delete();
    }
// Create User

    public function createUser(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }
    // Get Users
    public function getUser(){
        return User::get();
    }

    // Editing Admin
    public function editAdmin(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'userType' => $request->userType,
        ];
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id',$request->id)->update($data);
        return $user;
    }

    // Login Admin

    public function adminLogin(Request $request){
        // validate

        $credentials = $this->validate($request, [
            'email' => "bail|required|email",
            'password' => 'bail|required|min:6',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            if($user->role->isAdmin == 0){
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect Login details',
                ]);

            }
            return response()->json([
                'msg' => 'You are Logged in',
                'user'=> $user
            ]);
        }else {
            return response()->json([
                'msg' => 'Incorrect Login details',
            ]);
        }
    }

    public function createRole(Request $request){
        $this->validate($request, [
            'roleName'  => 'required'
        ]);

        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function getRole(){
        return Role::all();
    }
    public function editRole(Request $request){
        $this->validate($request, [
            'roleName' => 'required'
        ]);

        return Role::where('id', $request->id )->update([
            'roleName' => $request->roleName
        ]);
    }

    // Assign Role

    public function assignRole(Request $request){
        $this->validate($request, [
            'permission' => 'required',
            'id'         => 'required'
        ]);
        return Role::where('id', $request->id)->update([
            'permission'   => $request->permission
        ]);
    }

    // Upload the Blog Image

    public function uploadEditorImage(Request $request){
        $this->validate($request, [
            'image'      => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'), $picName);
        return response()->json([
            'success'  => 1,
            'file'     =>[
                'url'  => "http://127.0.0.1:8000/uploads/$picName"
        ]
        ]);
        return $picName;
    }

    public function createBlog(Request $request){
        $request->validate([
            'title'    => 'required',
            'post'     => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);



        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try{
            $blog = Blog::create([
                'title'            => $request->title,
                'post'             => $request->post,
                'post_excerpt'     => $request->post_excerpt,
                'user_id'          => Auth::user()->id,
                'metaDescription'  => $request->metaDescription,
                'jsonData'         => $request->jsonData,
            ]);
            // // insert blogcategories

            foreach($categories as $c){
                array_push($blogCategories, ['category_id'  => $c, 'blog_id' => $blog->id]);
            }
            BlogCategory::insert($blogCategories);

            // insert blogtags

            foreach($tags as $t){
                // print_r($t);
                array_push($blogTags, ['tag_id' => $t, 'blog_id' => $blog->id]);
            }
            BlogTag::insert($blogTags);
            DB::commit();
            return 'done';
        }catch(\Throwable $th){
            DB::rollback();
            return 'Not done';
        }
    }

    //  blog data
    public function  blogData(Request $request){
        return Blog::with('tag', 'cat')->orderBy('id', 'desc')->paginate($request->total);
    }

    public function deleteBlog(Request $request){
        return Blog::where('id', $request->id)->delete();
    }

    public function singelBlogItem(Request $request, $id){
        return Blog::with('tag', 'cat')->where('id', $id)->first();
    }

    // update blog

    public function updateBlog(Request $request, $id){
        $request->validate([
            'title'    => 'required',
            'post'     => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);



        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try{
            $blog = Blog::where('id', $id)->update([
                'title'            => $request->title,
                'post'             => $request->post,
                'post_excerpt'     => $request->post_excerpt,
                'user_id'          => Auth::user()->id,
                'metaDescription'  => $request->metaDescription,
                'jsonData'         => $request->jsonData,
            ]);
            // // insert blogcategories

            \Log::info("blog is $blog");
            foreach($categories as $c){
                array_push($blogCategories, ['category_id'  => $c, 'blog_id' => $id]);
            }
            BlogCategory::where('blog_id', $id)->delete();
            BlogCategory::insert($blogCategories);

            // insert blogtags

            foreach($tags as $t){
                // print_r($t);
                array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
            }
            BlogTag::where('tag_id', $id)->delete();
            BlogTag::insert($blogTags);
            DB::commit();
            return 'done';
        }catch(\Throwable $th){
            DB::rollback();
            return 'Not done';
        }
    }







    // public function slug(){
    //     $title = 'This is the nice title';

    //     return Blog::create([
    //         'title'            => $title,
    //         'post'             => 'some post',
    //         'post_excerpt'     => 'ahead',
    //         'user_id'          => 1,
    //         'metaDescription'  => 'ahead',
    //     ]);
    //     // return $title;
    // }


}
