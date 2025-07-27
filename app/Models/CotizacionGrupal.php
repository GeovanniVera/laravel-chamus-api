<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionGrupal extends Model
{
    protected $fillable = [
        'museum_id',
        'appointment_date',
        'start_hour',
        'end_hour',
        'total_people',
        'total_people_discount',
        'total_people_whitout_discount',
        'total_infants',
        'total_whit_discount',
        'total_whitout_discount',
        'price_total',
        'unique_id',
    ];

    /**
     * Define la relación con el modelo Museum.
     */
    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }
}
