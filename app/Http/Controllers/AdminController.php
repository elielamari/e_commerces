<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Brands;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
// use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Product;
// use Intervention\Image\Facades\Image;
use Intervention\Image\Laravel\Facades\Image;








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

         //Products function
         public function Products(){
            $products = Product::orderBy('created_at','DESC')->paginate(10);
            return view('admin.products',compact('products'));
  }

         public function addProduct(){
            $categories=Category::select('id','name')->orderBy('name')->get();
            $brands=Brands::select('id','name')->orderBy('name')->get();
            return view('admin.addProduct' ,compact('categories','brands'));

    }

public function saveProduct(Request $request){
        $request->validate([
            'name'=> 'required',
            'slug'=> 'required|unique:products,slug',
            'short_description'=> 'required',
            'description'=> 'required',
            'regular_price'=> 'required',
            'sale_price'=> 'required',
            'SKU'=> 'required',
            'stock_status'=> 'required',
            'featured'=> 'required',
            'quantity'=> 'required',
            'image'=> 'required|mimes:png,jpg,jpeg,jfif|max:2048',
            // 'images'=> 'required',
            'category_id'=> 'required',
            'brand_id'=> 'required'

        ]);
        $product = new Product();

        $product-> name = $request->name;
        $product-> slug = Str::slug($request->name);
        $product-> short_description = $request->short_description;
        $product-> description = $request->description;
        $product-> regular_price = $request->regular_price;
        $product-> sale_price = $request->sale_price;
        $product-> SKU = $request->SKU;
        $product-> stock_status = $request->stock_status;
        $product-> featured = $request->featured;
        $product-> quantity = $request->quantity;
        
        // $product-> name = $request->$name;
        $product-> category_id = $request->category_id;
        $product-> brand_id = $request->brand_id;

        // $current_timestamp = Carbon::new()->timestamp;
        $current_timestamp = now()->timestamp;

        if($request->hasFile('image')){

            $image = $request->file('image');
            $imagename = $current_timestamp . '-'. $image->extension();
            $this->GenerateProductImg($image,$imagename);
            $product->image = $imagename;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images')){
            $allowedfileextension = ['jpg','png','jpeg','jfif'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileextension);
                if($gcheck)
                {
                    $gfilename = $current_timestamp .'-'. $counter.'-'. $gextension;
                    $this->GenerateProductImg($file,$gfilename);
                    array_push($gallery_arr,$gfilename);
                    $counter = $counter + 1; 
                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('brand_message', 'Product added successfully');


    }
    public function GenerateProductImg($image, $imagename)
    {
        $destinationGenPath = public_path('uploads/product/GenerateProductImg');
        $destinationPath = public_path('uploads/product');

        // Ensure directories exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0775, true);
        }
        if (!file_exists($destinationGenPath)) {
            mkdir($destinationGenPath, 0775, true);
        }

        // Main image (540x689)
        $mainImg = Image::read($image->path());
        $mainImg->cover(540, 689, 'top')
                ->resize(540, 689, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($destinationPath . '/' . $imagename);

        // Reshape (140x140)
        $thumbImg = Image::read($image->path());
        $thumbImg->resize(140, 140, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($destinationGenPath . '/' . $imagename);
    }

    public function editproduct($id){
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brands::select('id','name')->orderBy('name')->get();

        return view('admin.editproduct',compact('product','categories','brands'));
    }

    public function productupdate(Request $request)
{
    //  Validation
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug,' . $request->id,
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'mimes:png,jpg,jpeg,jfif|max:2048',
        'category_id' => 'required',
        'brand_id' => 'required',
    ]);

    // Get product
    $product = Product::findOrFail($request->id);

    // Update fields
    $product->name = $request->name;
    $product->slug = Str::slug($request->slug); // use provided slug
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;

    $current_timestamp = now()->timestamp;

    // Handle main product image
    if ($request->hasFile('image')) {
        // Delete old images if they exist
        if (File::exists(public_path('uploads/product/' . $product->image))) {
            File::delete(public_path('uploads/product/' . $product->image));
        }

        if (File::exists(public_path('uploads/product/GenerateProductImg/' . $product->image))) {
            File::delete(public_path('uploads/product/GenerateProductImg/' . $product->image));
        }

        // Upload new image
        $image = $request->file('image');
        $imagename = $current_timestamp . '.' . $image->extension(); // dot before extension
        $this->GenerateProductImg($image, $imagename);
        $product->image = $imagename;
    }

    // Handle gallery images
    if ($request->hasFile('images')) {
        // Delete old gallery images
        if (!empty($product->images)) {
            foreach (explode(',', $product->images) as $ofile) {
                $ofile = trim($ofile);
                if (empty($ofile)) continue;

                $paths = [
                    public_path('uploads/product/' . $ofile),
                    public_path('uploads/product/GenerateProductImg/' . $ofile),
                ];

                foreach ($paths as $path) {
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
            }
        }

        // Upload new ones
        $gallery_arr = [];
        $allowedfileextension = ['jpg', 'png', 'jpeg', 'jfif'];
        $counter = 1;

        foreach ($request->file('images') as $file) {
            $gextension = $file->getClientOriginalExtension();
            if (in_array(strtolower($gextension), $allowedfileextension)) {
                $gfilename = $current_timestamp . '-' . $counter . '.' . $gextension; // dot added
                $this->GenerateProductImg($file, $gfilename);
                $gallery_arr[] = $gfilename;
                $counter++;
            }
        }

        $product->images = implode(',', $gallery_arr);
    }

    // Save product
    $product->save();

    return redirect()->route('admin.products')->with('brand_message', 'Product updated successfully.');
}

public function deleteproduct($id){

    $product = Product::find($id);
    if(File::exists(public_path('uploads/product').'/'.$product->image))
    {
        File::delete(public_path('uploads/product' . $product->image));
    }

    if(File::exists(public_path('uploads/product/GenerateProductImg').'/'.$product->image))
    {
        File::delete(public_path('uploads/product/GenerateProductImg' . $product->image));
    }

    foreach (explode(',', $product->images) as $ofile) 
        {
        $filePath = public_path('uploads/product/' . $ofile);
        $generatedFilePath = public_path('uploads/product/GenerateProductImg/' . $ofile);

        // Delete the original file if it exists
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete the generated image if it exists
        if (File::exists($generatedFilePath)) {
            File::delete($generatedFilePath);
        }
}


    $product->delete();
    return redirect()->route('admin.products')->with('brand_message', 'Product deleted successfully.');

}








      
    }



