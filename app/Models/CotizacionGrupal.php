<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionGrupal extends Model
{
    protected $fillable = [
        'museum_id',
        'start_hour',
        'end_hour',
        'total_people',
        'total_people_discount',
        'total_people_whitout_discount',
        'total_whit_discount',
        'total_whitout_discount',
        'price_total',
        'unique_id',
    ];

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }
}
