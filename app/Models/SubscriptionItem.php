<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model
{
    protected $fillable = [
        'subscription_id',
        'title',
        'unit',
        'size_unit',
        'quantity',
        'unit_price',
        'total_price',
    ];
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
