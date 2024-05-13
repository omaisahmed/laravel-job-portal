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

    // public function index()
    // {
    //     $posts = post::orderBy('created_at','DESC')->get();
    //     return view('admin.post.index',compact('posts'));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     $tags =tag::all();
    //     $categories =category::all();
    //     return view('admin.post.create',compact('tags','categories'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $this->validate($request,[
    //         'title'=>'required',
    //         'subtitle' => 'required',
    //         'slug' => 'required',
    //         'body' => 'required',
    //         'image' => 'nullable|max:1999',
    //         ]);

    //     //Handle file upload
    //     if($request->hasFile('image'))
    //     {
    //         // Get filename with the extension
    //         $filenameWithExt = $request->file('image')->getClientOriginalName();
    //         // Get just filename
    //         $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    //         // Get just ext
    //         $extension = $request->file('image')->getClientOriginalExtension();
    //         // Filename to store
    //         $fileNameToStore = $filename.'_'.time().'.'.$extension;
    //         // Upload image
    //         $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
    //     } else
    //     {
    //         $fileNameToStore = 'noimage.jpg';
    //     }

    //     //Create new post
    //     $post = new post;
    //     $post->image = $fileNameToStore;
    //     $post->title = $request->title;
    //     $post->subtitle = $request->subtitle;
    //     $post->slug = $request->slug;
    //     $post->body = $request->body;
    //     $post->status = $request->status;
    //     $post->save();
    //     // Many to many relation between Posts and Tags
    //     $post->tags()->sync($request->tags);
    //     // Many to many relation between Posts and Categories
    //     $post->categories()->sync($request->categories);
    //     return redirect(route('post.index'))->with('message', 'Added Post Successfully!!!!');
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $post = post::with('tags','categories')->where('id',$id)->first();
    //     $tags =tag::all();
    //     $categories =category::all();
    //     return view('admin.post.edit',compact('tags','categories','post'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request,[
    //         'title'=>'required',
    //         'subtitle' => 'required',
    //         'slug' => 'required',
    //         'body' => 'required',
    //         'image'=>'nullable|max:1999'
    //         ]);
    //    //Handle file upload
    //     if($request->hasFile('image'))
    //     {
    //         // Get filename with the extension
    //         $filenameWithExt = $request->file('image')->getClientOriginalName();
    //         // Get just filename
    //         $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    //         // Get just ext
    //         $extension = $request->file('image')->getClientOriginalExtension();
    //         // Filename to store
    //         $fileNameToStore = $filename.'_'.time().'.'.$extension;
    //         // Upload image
    //         $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
    //     } else
    //     {
    //         $fileNameToStore = 'noimage.jpg';
    //     }
    //     //Update file
    //     $post = post::find($id);
    //     $post->title = $request->title;
    //     $post->subtitle = $request->subtitle;
    //     $post->slug = $request->slug;
    //     $post->body = $request->body;
    //     $post->status = $request->status;
    //     if($request->hasFile('image'))
    //     {
    //         // Delete the old image if it's changed .
    //         if ($post->image != 'no_image.png')
    //         {
    //             Storage::delete('public/images/'.$post->image);
    //         }
    //         $post->image = $fileNameToStore;
    //     }
    //     $post->tags()->sync($request->tags);
    //     $post->categories()->sync($request->categories);
    //     $post->save();
    //     return redirect(route('post.index'))->with('message', 'Updated Post Successfully!!!!');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $posts = post::find($id);
    //     //Delete image from post
    //     if($posts->image != 'noimage.jpg')
    //     {
    //         Storage::delete('public/images/'.$posts->image);
    //     }
    //     //$posts->categories()->detach();
    //     //$posts->tags()->detach();
    //     $posts->delete();
    //     return redirect(route('post.index'))->with('message', 'Deleted Post Successfully!!!!');
    // }
}
