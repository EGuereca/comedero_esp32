<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comedero extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'mascota_id',
        'numero_serie',
        'cantidad_comida',
        'cantidad_agua',
        'cantidad_agua_servida',
        'cantidad_comida_servida',
        'humedad',
        'gases',
        'temperatua_agua',
        'mascota_cerca',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }
}
