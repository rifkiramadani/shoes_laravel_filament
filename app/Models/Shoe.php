<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ShoeSize;
use App\Models\ShoePhoto;
use Illuminate\Support\Str;
use App\Models\ProductTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shoe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'price',
        'stock',
        'category_id',
        'brand_id',
        'is_popular',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function shoePhotos()
    {
        return $this->hasMany(ShoePhoto::class);
    }

    public function shoeSizes()
    {
        return $this->hasMany(ShoeSize::class);
    }

    public function productTransactions()
    {
        return $this->hasMany(ProductTransaction::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
