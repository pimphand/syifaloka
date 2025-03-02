<?php

namespace App\Models;

use Database\Factories\SkuFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    /** @use HasFactory<SkuFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = ['name',
        'price',
        'stock',
        'image',
        'attributes',
        'options',
        'extras'
    ];
}
