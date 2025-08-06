<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Purchase;
use App\Models\Like;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'image',
        'condition_id',
        'brand_name',
        'description',
        'price'
    ];
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
