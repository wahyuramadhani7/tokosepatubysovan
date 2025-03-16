<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'subtotal',
        'ppn',
        'total',
        'customer_name',
        'customer_phone',
        'payment_method',
        'amount_paid',
        'change',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }
}