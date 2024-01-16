<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $productId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $comment = Comment::create([
            'product_id' => $productId,
            'author' => $user->name, // lub $user->email
            'user_id' => $user->id,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        // Aktualizacja produktu, aby zwracał wszystkie komentarze
        $product->load('comments');

        return response()->json(['message' => 'Comment added successfully', 'data' => $product->comments], 201);
    }

    public function index($productId)
    {
        $product = Product::with('comments')->find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['data' => $product->comments]);
    }

    public function indexComment()
    {
        $userId = Auth::id();
        
        // Zakładając, że masz relację 'product' w modelu Comment
        $userComments = Comment::where('user_id', $userId)
            ->select('id', 'product_id', 'user_id', 'content', 'rating', 'created_at', 'updated_at')
            ->get();

        return response()->json($userComments);
    }
    
}
