<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Shoe extends Model {
    protected $fillable = ['name', 'size', 'price', 'stock', 'barcode'];
}