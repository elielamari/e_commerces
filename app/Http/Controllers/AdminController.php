<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brands;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Category;








class AdminController extends Controller
{
    public function addBrand(){
        return view('admin.addbrand');
    }
    public function index(){
        return view('admin.dashboard');
    }

    


    public function saveBrand(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:brands,slug',
                'image' => 'mimes:png,jpeg,jpg,jfif|max:2048',
            ]);

            $brand = new Brands();
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name); 

            $image = $request->file('image');
            if ($image) {
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $brand->image = $imagename;
            }

            $brand->save();

            if ($image) {
                $image->move(public_path('uploads/brands'), $imagename);
            }

            return redirect()->route('admin.brands')->with('brand_message', 'Brand added successfully');
        }
  public function brands(){
    $brands=Brands::orderBy('id','DESC')->paginate(10);
    return view('admin.brands',compact('brands'));
  }
    
    public function editBrand($id){
        $brand=Brands::find($id);
        return view('admin.editbrand',compact('brand'));
    }


    public function brandupdate(Request $request)
        {
            $brand = Brands::findOrFail($request->id); // Ensures brand exists

            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:brands,slug,' . $brand->id, // Ignore current brand's slug
                'image' => 'nullable|mimes:png,jpeg,jpg,jfif|max:2048',
            ]);

            $brand->name = $request->name;
            $brand->slug = Str::slug($request->slug); // Use slug from form, not regenerated

            // Image upload handling
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($brand->image && File::exists(public_path('uploads/brands/' . $brand->image))) {
                    File::delete(public_path('uploads/brands/' . $brand->image));
                }

                $image = $request->file('image');
                $imagename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/brands'), $imagename);

                $brand->image = $imagename;
            }
            $brand->save();
            return redirect()->route('admin.brands')->with('brand_message', 'Brand updated successfully');

        }

        public function deleteBrand($id){
            $brand=Brands::find($id);
            if(File::exists(public_path('uploads/brands/'.$brand->image))){
                File::delete(public_path('uploads/brands/'.$brand->image));
            }
            $brand->delete();
            return redirect()->route('admin.brands')->with('brand_message','Brand deleted successfully');
         }
           
        public function categories(){
            $categories = Category::orderBy('id', 'DESC')->paginate(10);
            return view('admin.categories', compact('categories'));
         }
         public function addCategories(){
            return view('admin.addcategories');
         }

         public function saveCategory(Request $request)
        {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:brands,slug',
                'image' => 'mimes:png,jpeg,jpg,jfif|max:2048',
            ]);

            $category = new Category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name); 

            $image = $request->file('image');
            if ($image) {
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $category->image = $imagename;
            }

            $category->save();

            if ($image) {
                $image->move(public_path('uploads/category'), $imagename);
            }

            return redirect()->route('admin.categories')->with('category_message', 'Brand added successfully');
        }

        public function editCategories($id){
        $category=Category::find($id);
        return view('admin.editCategories',compact('category'));
    }


        public function updateCategories(Request $request)
        {
            $category = Category::findOrFail($request->id); // Ensures brand exists

            $request->validate([
                'name' => 'required',
                'slug' => 'required|unique:categories,slug,' . $category->id, // Ignore current brand's slug
                'image' => 'nullable|mimes:png,jpeg,jpg,jfif|max:2048',
            ]);

            $category->name = $request->name;
            $category->slug = Str::slug($request->slug); // Use slug from form, not regenerated

            // Image upload handling
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && File::exists(public_path('uploads/category/' . $category->image))) {
                    File::delete(public_path('uploads/category/' . $category->image));
                }

                $image = $request->file('image');
                $imagename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/category'), $imagename);

                $category->image = $imagename;
            }
            $category->save();
            return redirect()->route('admin.categories')->with('Category_message', 'Categories updated successfully');

        }
        
           
         public function deleteCategories($id){
            $category=Category::find($id);
            if(File::exists(public_path('uploads/category/'.$category->image))){
                File::delete(public_path('uploads/category/'.$category->image));
            }
            $category->delete();
            return redirect()->route('admin.categories')->with('category_message','category deleted successfully');
         }
    

    }



