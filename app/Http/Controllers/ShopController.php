<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Product;

class ShopController extends Controller
{
    //
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('shop' , compact('products'));
    }
}
