<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Określenie atrybutów, które można masowo przypisywać.
    protected $fillable = ['product_id', 'user_id', 'author', 'content', 'rating'];

    // Automatyczne zarządzanie datami utworzenia i aktualizacji.
    public $timestamps = true;

    // Relacja do użytkownika (opcjonalnie)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacja do produktu (opcjonalnie)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ... reszta modelu
}
