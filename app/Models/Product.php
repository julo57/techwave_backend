<?php
namespace App\Models;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function comments()
{
    return $this->hasMany(Comment::class);
}
}
