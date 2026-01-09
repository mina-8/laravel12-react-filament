<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Item extends Model
{
    use HasTranslations;
    protected $fillable = [
        'title',
        'is_active'
    ];
    public $translatable = ['title'];
    protected $casts = [
        'title' => 'array',
        'is_active' => 'boolean'
    ];



    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class);
    }
}
