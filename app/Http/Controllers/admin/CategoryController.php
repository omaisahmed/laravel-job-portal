<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('created_at','DESC')->paginate(10);
        return view('admin.categories.list',[
            'categories' => $categories
        ]);
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {

        $rules = [
            'name'=>'required',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $category = new Category;
            $category->name = $request->name;
            $category->status = $request->status;
            $category->save();

            session()->flash('success','Category created successfully.');

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

    public function edit($id) {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit',[
            'category' => $category
        ]);
    }

    public function update($id, Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);

        if ($validator->passes()) {
            $category = Category::find($id);
            $category->name = $request->name;
            $category->status = $request->status;
            $category->save();

            session()->flash('success','Category updated successfully.');

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

    public function destroy(Request $request){
        $id = $request->id;
        $category = Category::find($id);

        if ($category == null) {
            session()->flash('error','Category not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $category->delete();
        session()->flash('success','Category deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
