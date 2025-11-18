<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'sender_id',
        'body',
        'image',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
