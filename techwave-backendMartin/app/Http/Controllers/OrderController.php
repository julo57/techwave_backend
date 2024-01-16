<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnModel;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Próba stworzenia zamówienia');

        $user = Auth::user();

        if (!$user) {
            Log::info('Zamówienie dla niezalogowanego użytkownika');
            // Tutaj można dodać kod do przekierowania na finalną stronę
            return response()->json(['message' => 'Order processed for guest user'], 200);
        }

        Log::info('Użytkownik jest zalogowany', ['user_id' => $user->id]);

        $items = $request->input('items');
        $totalAmount = $request->input('totalAmount');
        $address = $request->input('address');

        if (empty($items)) {
            Log::info('Zamówienie nie zawiera żadnych przedmiotów');
            return response()->json(['message' => 'No items provided'], 400);
        }

        $createdOrders = [];

        foreach ($items as $item) {
            if (!isset($item['products_id'])) {
                Log::error('Brakuje klucza "products_id" w elemencie', ['item' => $item]);
                continue;
            }
    
            if (!isset($item['productname'])) {
                Log::error('Brakuje klucza "productname" w elemencie', ['item' => $item]);
                continue;
            }
    
            if (!isset($item['Price'])) {
                Log::error('Brakuje klucza "Price" w elemencie', ['item' => $item]);
                continue;
            }
    
            $productId = $item['products_id'];
            $quantity = $item['quantity'] ?? 0;
            $productName = $item['productname'];
            $price = $item['Price'];
    
            try {
                $product = Product::find($productId);
    
                if (!$product) {
                    Log::warning('Produkt nie znaleziony', ['products_id' => $productId]);
                    continue;
                }
    
                $order = Order::create([
                    'products_id' => $productId,
                    'user_id' => $user->id,
                    'quantity' => $quantity,
                    'productname' => $productName,
                    'Price' => $price
                ]);
    
                Log::info('Zamówienie zostało stworzone', ['order_id' => $order->id]);
    
                array_push($createdOrders, $order);
    
            } catch (\Exception $e) {
                Log::error('Błąd podczas tworzenia zamówienia', [
                    'error' => $e->getMessage(),
                    'products_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        }
    
        if (empty($createdOrders)) {
            Log::info('Nie utworzono żadnych zamówień');
            return response()->json(['message' => 'No orders were created'], 400);
        }

        Log::info('Zamówienia zostały pomyślnie dodane', ['orders' => $createdOrders]);
        return response()->json([
            'message' => 'Orders added successfully',
            'orders' => $createdOrders
        ], 201);
    }
    public function index()
    {
        // Pobranie ID zalogowanego użytkownika
        $userId = Auth::id();

        // Pobierz zamówienia dla zalogowanego użytkownika, wybierając określone kolumny
        $orders = Order::where('user_id', $userId)
                    ->select('id', 'products_id', 'created_at', 'updated_at', 'productname', 'Price', 'quantity')
                    ->get();

        return response()->json($orders);
    }

        public function indexReturns()
    {
        $userId = Auth::id();
        $returns = ReturnModel::where('user_id', $userId)
                    ->select('id', 'products_id', 'created_at', 'updated_at', 'productname', 'Price', 'quantity')
                    ->get();

        return response()->json($returns);
    }


    public function returnOrder($orderId)
    {
        $order = Order::find($orderId);

        if ($order) {
            $return = new ReturnModel(); 
            $return->products_id = $order->products_id;
            $return->user_id = $order->user_id;
            $return->productname = $order->productname;
            $return->Price = $order->Price;
            $return->quantity = $order->quantity;
            $return->save();

            $order->delete(); 
            return response()->json(['message' => 'Order returned successfully']);
        }

        return response()->json(['message' => 'Order not found'], 404);
    }
}
