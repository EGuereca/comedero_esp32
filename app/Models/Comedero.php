<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comedero extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'comederos';

    protected $fillable = [
        'usuario_id',
        'mascota_id',
        'cantidad_comida',
        'cantidad_agua',
        'cantidad_agua_servida',
        'cantidad_comida_servida',
        'humedad',
        'gases',
        'temperatua_agua',
        'mascota_cerca',
        'estado',
        "deleted_at"
    ];

    protected $hidden = [
        "deleted_at",
        "created_at",
        "updated_at"
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
