<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
//mustra la lista de los productos y uqe tengan stock
    public function index()
    {
        $products = Product::select(['id', 'nombre_producto', 'descripcion', 'precio_unitario', 'stock'])
                            ->where('stock', '>', 0) 
                            ->orderBy('nombre_producto', 'asc')
                            ->get();

        return response()->json($products);
    }


}