<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'products_id',
        'user_id',
        'quantity',
        'productname',
        'Price',
        // Dodaj tutaj inne pola, które chcesz umożliwić do masowego przypisania
    ];

    // Możesz tutaj dodać dodatkowe metody lub relacje modelu, jeśli są potrzebne
}
