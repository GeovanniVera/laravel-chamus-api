<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'museum_id'
    ];

    protected $cast = [
        'museum_id' => 'integer'
    ];

    /**
     * Relation ship with the Museum Model.
     */

    public function museum() : BelongsTo{
        return $this->belongsTo(Museum::class);
    }
}
