<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasTranslations;
    protected $fillable = ['title', 'content', 'price', 'is_active'];
    public $translatable = ['title', 'content'];
    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'is_active' => 'boolean'
    ];

    public function items()
    {
        return $this->belongsToMany(ItemUnit::class, 'package_items', 'package_id', 'item_unit_id')
        ->withPivot('quantity')
        ->withTimestamps();
    }
}
