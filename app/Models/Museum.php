<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Museum extends Model
{
    /** @use HasFactory<\Database\Factories\MuseumFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'opening_time',
        'clossing_time',
        'latitude',
        'longitude',
        'description',
        'ticket_price',
        'url',
        'status'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'ticket_price' => 'float',
    ];

    /**
     * Relationship with the other models can be defined here.
     * for example: a museum has many rooms,
     * for example: a museum belongs to user,
     * for example: a museum belongs to many categories
     * for example: a museum has belongs to many discounts
     *
     */

     public function rooms() : HasMany {
        return $this->hasMany(Room::class);
     }

     public function user(){
        return $this->belongsTo(User::class);
     }

     public function discounts(){
        return $this->belongsToMany(Discount::class, 'discount_museum', 'museum_id', 'discount_id')
                    ->withPivot('description');
     }

     public function categories(){
        return $this->belongsToMany(Category::class, 'category_museum', 'museum_id', 'category_id');
     }

}
