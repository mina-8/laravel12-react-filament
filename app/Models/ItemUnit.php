<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    protected $fillable = [
        'item_id',
        'size_unit',
        'unit',
        'price',
        'is_active'
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_items', 'item_unit_id', 'package_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
