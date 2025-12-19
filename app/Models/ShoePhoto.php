<?php

namespace App\Models;

use App\Models\Shoe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoePhoto extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shoe_id',
        'photo',
    ];

    public function shoe()
    {
        return $this->belongsTo(Shoe::class, 'shoe_id');
    }
}
