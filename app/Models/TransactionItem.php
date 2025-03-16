<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'shoe_id',
        'barcode',
        'name',
        'price',
        'quantity',
        'total',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}