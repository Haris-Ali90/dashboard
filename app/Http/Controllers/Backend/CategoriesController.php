<?php

namespace App\Http\Controllers\Backend;

use Config;

use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Http\Requests\Backend\CategoryRequest;
use Illuminate\Support\Facades\Request as FacadeRequest;
use App\Http\Controllers\Backend\BackendController;
use Illuminate\Support\Facades\Auth;
//use Validator;

use App\Category;
use App\UserEntities;

class CategoriesController extends BackendController
{
    /**
     * Get categories
     */
    public function getIndex()
    {
        $categories = Category::where('parent_id',0)->get();
        return backend_view( 'categories.index', compact('categories') );
    }

    /**
     * show category edit form
     */
    public function edit(Category $category)
    {
        return backend_view('categories.edit', compact('category'));
    }

    /**
     * show category add form
     */
    public function add()
    {
        return backend_view('categories.add');
    }

    /**
     * Create category
     */
    public function create(Request $request)
    {
        $data = $request->all();
       if ($request->hasFile('product_image')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('product_image')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.categoryPicPath'));
            $request->file('product_image')->move($path, $imageName);

            $data['product_image'] = $imageName;
        }
        else
        {
            $imageName="default.png";
            $data['product_image'] = $imageName;
            
        }
        Category::create( $data );

        session()->flash('alert-success', 'Category has been created successfully!');
            return redirect( 'backend/category/add/');
    }

    /**
     * Update  category
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->all();
        if ($request->hasFile('product_image')) {
            $imageName =  \Illuminate\Support\Str::random(12) . '.' . $request->file('product_image')->getClientOriginalExtension();
            $path = public_path(Config::get('constants.front.dir.categoryPicPath'));
            $request->file('product_image')->move($path, $imageName);

            $data['product_image'] = $imageName;
        }
        $category->update( $data );

        session()->flash('alert-success', 'Category has been updated successfully!');
        return redirect( 'backend/category/edit/' . $category->id );
    }

    /**
     * delete category
     */
    public function destroy(Category $category)
    {   
        $id = $category->id	;	
        $category->delete();
		Category::where('parent_id',$id)->delete();

        session()->flash('alert-success', 'Category has been deleted successfully!');
        return redirect( 'backend/categories' );
    }

}
