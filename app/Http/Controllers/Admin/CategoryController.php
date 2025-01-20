<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return view('admin.category.index');
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(CategoryFormRequest $request){

        $validatedData = $request->validated();

        $category = new Category;
        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        $uploadPath = '/uploads/category/'.$category->image;
        if ($request->hasFile('image')) {
            $uploadPath = 'uploads/category/'; // Define upload directory
        
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);
        
            $file->move(public_path($uploadPath), $filename); // Ensure the file is moved to the public directory
            $category->image = $uploadPath . $filename; // Save relative path to database
        }
        

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_keyword = $validatedData['meta_keyword'];
        $category->meta_description = $validatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';
        $category->save();

        return redirect('admin/category')->with('message','Category Added Successfully');

    }

    public function edit(Category $category) {

        return view('admin.category.edit', compact('category'));
        
    }

    public function update(CategoryFormRequest $request, $category) {
        
        $validatedData = $request->validated();

        $category = Category::findOrFail($category);

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        if ($request->hasFile('image')){

            $uploadPath = '/uploads/category/';

            $path = 'uploads/category/'.$category->image;

            if (File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);

            $file->move('uploads/category', $filename);
            $category->image = $uploadPath.$filename;
        }

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_keyword = $validatedData['meta_keyword'];
        $category->meta_description = $validatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';
        $category->update();

        return redirect('admin/category')->with('message','Category Updated Successfully');
    }
}
