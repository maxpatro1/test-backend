<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'merchant_id',
        'merchant_key',
        'limit',
        'current_amount'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
