<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['name', 'description', 'price', 'category_id','image'];
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($product) {
            $product->skus()->delete();
        });
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function skus(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sku::class);
    }
}
