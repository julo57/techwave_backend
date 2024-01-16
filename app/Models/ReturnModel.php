<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'products_id',
        'user_id',
        'quantity',
        'productname',
        'Price',
        // Dodaj tutaj inne pola, które chcesz umożliwić do masowego przypisania
    ];
    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'products_id');
    // }
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    // public $timestamps = false;


    // Możesz tutaj dodać dodatkowe metody lub relacje modelu, jeśli są potrzebne
}