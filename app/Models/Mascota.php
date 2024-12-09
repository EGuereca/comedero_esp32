<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nombre',
        'tipo',
        'animal',
        'comidas_diarias'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function comederos()
    {
        return $this->hasMany(Comedero::class, 'mascota_id');
    }
}
