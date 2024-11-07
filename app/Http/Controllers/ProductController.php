<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // This method will show products page
    public function index(Request $request) {
//        $products = Product::orderBy('created_at','DESC')->get();
//
//        return view('products.index',[
//            'products' => $products
//        ]);
        // Get filter values from the request
        $query = Product::query();

        // Filter by product_id if provided
//        if ($request->filled('product_id')) {
//            $query->where('product_id', 'like', '%' . $request->product_id . '%');
//        }

        // Filter by name if provided
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by price if provided
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting based on 'sort_price' parameter
        if ($request->filled('sort_price')) {
            if ($request->sort_price === 'low_to_high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort_price === 'high_to_low') {
                $query->orderBy('price', 'desc');
            }
        }

        // Filter by description if provided
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Paginate the results to display
        $products = $query->paginate(2);

        // Return view with filtered products
        return view('products.index', compact('products'));
    }

    // This method will show create product page
    public function create() {
        return view('products.create');
    }

    // This method will store a product in db
    public function store(Request $request) {
        $rules = [
            'product_id' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required|numeric'
        ];


        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

//        if ($validator->fails()) {
//            return redirect()->route('products.create')->withInput()->withErrors($validator);
//        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // here we will insert product in db
        $product = new Product();
        $product->product_id = $request->input('product_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->save();

        if ($request->image != "") {
            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads/products'),$imageName);

            // Save image name in database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    public function show(Product $product, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            // Handle the case where no product is found
            abort(404, 'Product not found');
        }

        return view('products.show', compact('product'));
    }

    // This method will show edit product page
    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('products.edit',[
            'product' => $product
        ]);
    }

    // This method will update a product
    public function update($id, Request $request) {

        $product = Product::findOrFail($id);


        $rules = [
            'product_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric'
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);


        if ($validator->fails()) {
            return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
        }

        // here we will update product
        $product->product_id = $request->input('product_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->save();
        if ($request->image != "") {

            // delete old image
            File::delete(public_path('uploads/products/'.$product->image));

            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads/products'),$imageName);

            // Save image name in database
            $product->image = $imageName;
            $product->save();
        }


        return redirect()->route('products.index')->with('success','Product updated successfully.');
    }

    // This method will delete a product
    public function destroy($id) {
        $product = Product::findOrFail($id);

       // delete image
       File::delete(public_path('uploads/products/'.$product->image));

       // delete product from database
       $product->delete();

       return redirect()->route('products.index')->with('success','Product deleted successfully.');
    }
}
