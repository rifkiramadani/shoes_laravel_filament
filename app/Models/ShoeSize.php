<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoeSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'size',
        'shoe_id'
    ];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
}
