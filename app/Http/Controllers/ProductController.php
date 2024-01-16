<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the specified product with detailed information.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductDetails($id)
    {
        // Find the product by id
        $product = Product::find($id);

        // If the product is found, return it, otherwise return a 404 response
        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * Display a listing of the products based on various filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterProducts(Request $request)
    {
        // Start a query builder instance for the Product model
        $query = Product::query();

        // Price range filter
        if ($request->has('priceMin')) {
            $query->where('price', '>=', $request->get('priceMin'));
        }
        if ($request->has('priceMax')) {
            $query->where('price', '<=', $request->get('priceMax'));
        }

        // Category filter
        if ($request->has('category')) {
            $categories = explode(',', $request->get('category'));
            $query->whereIn('Category', $categories);
        }

        // RAM filter
        if ($request->has('ram')) {
            $rams = explode(',', $request->get('ram'));
            $query->whereIn('RAM', $rams);
        }

        // Storage filter
        if ($request->has('storage')) {
            $storages = explode(',', $request->get('storage'));
            $query->whereIn('Storage', $storages); // Updated column name to 'Storage'
        }

        // Search filter (optional, based on your needs)
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->where('name', 'like', $searchTerm . '%');
        }

        // Diagonal filter
        if ($request->has('diagonal')) {
            $diagonals = explode(',', $request->get('diagonal'));
            $query->whereIn('diagonal', $diagonals);
        }

        // Matrix filter
        if ($request->has('matrix')) {
            $matrices = explode(',', $request->get('matrix'));
            $query->whereIn('matrix', $matrices);
        }

        // Resolution filter
        if ($request->has('resolution')) {
            $resolutions = explode(',', $request->get('resolution'));
            $query->whereIn('resolution', $resolutions);
        }

        // Energy class filter
        if ($request->has('energyclass')) {
            $energyclasses = explode(',', $request->get('energyclass'));
            $query->whereIn('energyclass', $energyclasses);
        }

        // Execute the query and get the products
        $products = $query->get([
            'id', 'photo', 'name', 'Storage', 'Screen', 'RAM', 'Processor', 'price', 'reviews', 'rating', 'description', 'Category'
        ]);

        return response()->json($products);
    }

    /**
     * Display a listing of the products with search functionality.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Start a query builder instance for the Product model
        $query = Product::query();

        // If a search term is provided, modify the query to include search conditions
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            // This will only include products where the name starts with the search term
            $query->where('name', 'like', $searchTerm . '%');
        }

        // Execute the query and get the products with the specified columns
        $products = $query->get([
            'id', 'photo', 'name', 'Storage', 'Screen', 'RAM', 'Processor', 'price', 'reviews', 'rating', 'description', 'Category'
        ]);

        if ($request->has('printingtechnology')) {
            $query->where('Printingtechnology', 'like', '%' . $request->get('printingtechnology') . '%');
        }
    
        if ($request->has('interfaces')) {
            $query->where('Interfaces', 'like', '%' . $request->get('interfaces') . '%');
        }
    
        if ($request->has('printspeed')) {
            $query->where('Printspeed', '=', $request->get('printspeed'));
        }
    
        if ($request->has('duplexprinting')) {
            $query->where('Duplexprinting', 'like', '%' . $request->get('duplexprinting') . '%');
        }
    
        if ($request->has('connection')) {
            $query->where('Connection', 'like', '%' . $request->get('connection') . '%');
        }
    
        if ($request->has('microphone')) {
            $query->where('Microphone', 'like', '%' . $request->get('microphone') . '%');
        }
    
        if ($request->has('noisecancelling')) {
            $query->where('noisecancelling', 'like', '%' . $request->get('noisecancelling') . '%');
        }
    
        if ($request->has('headphonetype')) {
            $query->where('Headphonetype', 'like', '%' . $request->get('headphonetype') . '%');
        }
    
        // Execute the query and get the products
        $products = $query->get([
            'id', 'photo', 'name', 'Storage', 'Screen', 'RAM', 'Processor', 'price', 'reviews', 'rating', 'description', 'Category',
            // Include new fields in the select
            'Printingtechnology', 'Interfaces', 'Printspeed', 'Duplexprinting', 'Connection', 'Microphone', 'noisecancelling', 'Headphonetype'
        ]);
    
        return response()->json($products);
    }

    /**
     * Display the specified product along with its comments.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the product by id and load its comments relationship
        $product = Product::with('comments')->find($id);

        // If the product is found, return it, otherwise return a 404 response
        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // You can add more methods here as needed, such as create, update, delete, etc.
}
