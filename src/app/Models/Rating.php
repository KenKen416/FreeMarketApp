<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Purchase;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rater_id',
        'ratee_id',
        'purchase_id',
        'score',
    ];

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function ratee()
    {
        return $this->belongsTo(User::class, 'ratee_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
