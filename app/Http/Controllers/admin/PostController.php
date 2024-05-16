<?php

namespace App\Http\Controllers\admin;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with('user')->orderBy('created_at','DESC')->paginate(10);
        return view('admin.posts.list',[
            'posts' => $posts
        ]);
    }

    public function create() {
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.posts.create',[
            'categories' => $categories
        ]);
    }

    public function edit($id) {
        $post = Post::findOrFail($id);
        $categories = Category::orderBy('name','ASC')->get();

        return view('admin.posts.edit',[
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request) {

        $rules = [
            'title'=>'required',
            'subtitle' => 'required',
            'slug' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'body' => 'required',
            'image' => 'required|image',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('/assets/images/blogs/'), $imageName);

            $post = new Post;
            $post->user_id = auth()->user()->id;
            $post->image = $imageName;
            $post->title = $request->title;
            $post->subtitle = $request->subtitle;
            $post->slug = $request->slug;
            $post->category_id = $request->category_id;
            $post->body = $request->body;
            $post->status = $request->status;
            $post->save();

            session()->flash('success','Post created successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function update(Request $request, $id) {

        $rules = [
            'title'=>'required',
            'subtitle' => 'required',
            'slug' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'body' => 'required',
            'image' => 'nullable|max:1999',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $post = Post::find($id);
            $post->user_id = auth()->user()->id;
            $post->title = $request->title;
            $post->subtitle = $request->subtitle;
            $post->slug = $request->slug;
            $post->category_id = $request->category_id;
            $post->body = $request->body;
            $post->status = $request->status;

            if ($request->hasFile('image')) {
                if ($post->image != null) {
                    $image_path = public_path('assets/images/blogs/' . $post->image);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $image->move(public_path('/assets/images/blogs/'), $imageName);
                $post->image = $imageName;
            }

            $post->save();
            // Many to many relation between Posts and Tags
            // $post->tags()->sync($request->tags);
            // Many to many relation between Posts and Categories
            // $post->categories()->sync($request->categories);

            session()->flash('success','Post updated successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;

        $post = Post::find($id);

        if ($post == null) {
            session()->flash('error','Either post deleted or not found');
            return response()->json([
                'status' => false
            ]);
        }

        if($post->image != null)
        {
            $image_path = public_path('assets/images/blogs/'.$post->image);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
        //$post->categories()->detach();
        //$post->tags()->detach();

        $post->delete();
        session()->flash('success','Post deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }

}
