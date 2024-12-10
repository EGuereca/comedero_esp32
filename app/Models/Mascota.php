<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mascota extends Model
{


    use HasFactory, SoftDeletes;
    protected $table = 'mascotas';
    protected $fillable = [
        'usuario_id',
        'nombre',
        'tipo',
        'animal',
        'comidas_diarias'
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
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
