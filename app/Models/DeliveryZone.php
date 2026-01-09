<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = ['name', 'polygon'];

    protected $casts = [
        'polygon' => 'array',
    ];
}
