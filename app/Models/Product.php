<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'product_id';

    public function getRouteKeyName()
    {
        return 'product_id';
    }

    protected $fillable = [
        'name', 'description', 'category', 'price', 'stok', 'image'
    ];

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id', 'product_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }
}
