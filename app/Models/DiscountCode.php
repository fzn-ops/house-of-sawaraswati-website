<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'label', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value'     => 'integer',
    ];
}
