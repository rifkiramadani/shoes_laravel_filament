<?php

namespace App\Models;

use App\Models\Shoe;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
    ];

    public function shoes()
    {
        return $this->hasMany(Shoe::class);
    }

    public function setNameAttribute($value)
    {
        $this->attribute['name'] = $value;
        $this->attribute['slug'] = Str::slug($value);
    }
}
