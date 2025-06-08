<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory;

    protected $fillable = [
        'discount'
    ];

    public function museums(): BelongsToMany {
        return $this->belongsToMany(Museum::class);
    }
}
