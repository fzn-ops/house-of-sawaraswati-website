<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    public function getRouteKeyName()
    {
        return 'product_id';
    }

    protected $fillable = [
        'name', 'description', 'price', 'stok', 'image'
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }
}
